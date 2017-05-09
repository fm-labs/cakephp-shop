<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Core\App;
use Cake\Core\StaticConfigTrait;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotImplementedException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Utility\Text;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Checkout\CheckoutStepRegistry;
use Shop\Lib\Shop;
use Shop\Model\Entity\ShopAddress;
use Shop\Model\Entity\ShopCustomer;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderAddress;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutComponent
 *
 * @package Shop\Controller\Component
 * @property ShopComponent $Shop
 * @property CartComponent $Cart
 */
class CheckoutComponent extends Component
{

    /**
     * @var array
     */
    public $components = ['Shop.Shop', 'Shop.Cart'];

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * @var CheckoutStepRegistry
     */
    protected $_stepRegistry;

    /**
     * List of configured steps.
     *
     * @var array
     */
    protected $_steps = [];

    /**
     * Active step.
     *
     * @var CheckoutStepInterface
     */
    protected $_active;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        //$this->ShopOrders = $this->_registry->getController()->loadModel('Shop.ShopOrders');
        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
        $this->_stepRegistry = new CheckoutStepRegistry($this);

        $steps = (isset($config['steps'])) ? $config['steps'] : [];
        $steps = ($steps) ?: (array) Shop::config('Shop.Checkout.Steps');

        // check if there are any checkout steps
        if (count($steps) < 1) {
            throw new InternalErrorException('Checkout: Checkout steps NOT configured');
        }

        foreach ($steps as $step => $config) {
            if (!$this->_stepRegistry->has($step)) {
                $this->_stepRegistry->load($step, $config);
            }
        }
        $this->_steps = $steps;
    }

    /**
     * @return \Cake\Controller\Controller
     */
    public function getController()
    {
        return $this->_registry->getController();
    }

    /**
     * Describe all steps with complete state.
     *
     * @return array
     */
    public function describeSteps()
    {
        $steps = [];
        $order = $this->getOrder();
        foreach (array_keys($this->_steps) as $stepId) {
            $step = $this->getStep($stepId);
            $steps[$stepId] = [
                'action' => $stepId,
                'title' => $step->getTitle(),
                'is_complete' => ($order) ? $step->isComplete() : false,
                'url' => $step->getUrl(),
                'icon' => null
            ];
        }
        return $steps;
    }

    /**
     * Get step by id.
     *
     * @param $stepId
     * @return CheckoutStepInterface
     */
    public function getStep($stepId)
    {
        if (!$this->_stepRegistry->has($stepId)) {
            throw new \InvalidArgumentException('Step ' . $stepId . ' is not registered');
        }

        return $this->_stepRegistry->get($stepId);
    }

    /**
     * Checks if previous steps have been completed.
     *
     * @param $stepId
     * @return bool
     */
    public function checkStep($stepId)
    {
        $complete = true;
        foreach ($this->_steps as $_stepId => $step) {
            if ($stepId == $_stepId) {
                break;
            }
            $complete = $complete && $this->getStep($_stepId)->isComplete();
            if ($complete !== true) {
                return $complete;
            }
        }
        return $complete;
    }

    /**
     * @return CheckoutStepInterface
     */
    public function nextStep(/* $startFromActive = false */)
    {
        //$activeId = ($startFromActive && $this->_active) ? $this->_active->getId() : false;

        foreach ($this->_steps as $stepId => $step) {
            // skip until active step, if activeId is set
            /*
            if ($activeId !== false && $activeId != $stepId) {
                debug("skip step: " . $stepId);
                continue;
            }
            $activeId = false;
            */

            if (!$this->getStep($stepId)->isComplete()) {
                //debug("step not complete: " . $stepId);
                return $this->getStep($stepId);
            }
            //debug("step complete: " . $stepId);
        }
    }

    /**
     * Execute step in controller context
     *
     * @param CheckoutStepInterface $step
     * @return mixed
     */
    public function executeStep(CheckoutStepInterface $step)
    {

        // before step
        $event = $this->getController()->eventManager()->dispatch(new Event('Shop.Checkout.beforeStep', $this, compact('step')));
        if ($event->result instanceof Response) {
            return $event->result;
        } elseif ($event->result instanceof CheckoutStepInterface) {
            $step = $event->result;
        }

        // execute
        $this->_setActiveStep($step);
        $response = $step->execute($this->_registry->getController());


        return $response;
    }

    /**
     * Get next step and call it's 'next' method and redirect to next step
     */
    public function next()
    {
        if ($this->_active) {
            // after
            $event = $this->getController()->eventManager()->dispatch(new Event('Shop.Checkout.afterStep', $this, ['step' => $this->_active]));
        }

        return $this->redirectNext();
    }


    /**
     * Set the active step.
     *
     * @param CheckoutStepInterface $step
     */
    protected function _setActiveStep(CheckoutStepInterface $step)
    {
        $this->_active = $step;
        $this->request->session()->write('Shop.Checkout.Step', ($step) ? $step->toArray() : null);
    }

    /**
     * Get url of next step. Or NULL if no step follows.
     *
     * @return null|array|string
     */
    public function redirectUrl()
    {
        $step = $this->nextStep();
        return ($step) ? $step->getUrl() : null;
    }

    /**
     * Redirect to next step. Or redirect to checkout index page
     * @TODO Check: Aren't we infinitly looping here, as checkout index itself redirects to next action?!
     *
     * @return \Cake\Network\Response|null
     */
    public function redirectNext()
    {
        $redirect = $this->redirectUrl();
        //$redirect = null;
        $redirect = ($redirect) ?: ['controller' => 'Checkout', 'action' => 'index'];
        return $this->_registry->getController()->redirect($redirect);
    }

    /**
     * Get cart order.
     *
     * @return ShopOrder
     */
    public function &getOrder()
    {
        return $this->Cart->getOrder();
    }

    /**
     * Set order in cart.
     *
     * @param ShopOrder $order
     * @param bool $update
     * @return $this
     */
    public function setOrder(ShopOrder $order, $update = true)
    {
        $this->Cart->setOrder($order, $update);
        return $this;
    }

    public function reloadOrder()
    {
        $this->Cart->reloadOrder();
        return $this;
    }

    /**
     * Submit order.
     *
     * @return bool|\Cake\Datasource\EntityInterface|mixed|ShopOrder
     * @throws \Exception
     */
    public function submitOrder()
    {
        if (!$this->getOrder()) {
            return false;
        }
        return $this->ShopOrders->submitOrder($this->getOrder());
    }

    /**
     * Reset checkout
     *
     * @return bool
     * @TOODO Implement CheckoutComponent::reset() method
     */
    public function resetOrder()
    {
        if (!$this->getOrder()) {
            return false;
        }

        throw new NotImplementedException('Implement CheckoutComponent::reset() method');
    }

    /**
     * Set order billing address.
     *
     * @param ShopOrderAddress $address
     * @return bool|\Cake\Datasource\EntityInterface|ShopOrderAddress
     */
    public function setBillingAddress(ShopOrderAddress $address)
    {
        return $this->ShopOrders->setOrderAddress($this->getOrder(), $address, 'B');
    }

    /**
     * Set order shipping address.
     *
     * @param ShopOrderAddress $address
     * @return bool|\Cake\Datasource\EntityInterface|ShopOrderAddress
     */
    public function setShippingAddress(ShopOrderAddress $address)
    {
        return $this->ShopOrders->setOrderAddress($this->getOrder(), $address, 'S');
    }


    public function setPaymentType($type, array $data)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order shipping type: No order');
            return false;
        }

        $paymentType = (isset($data['payment_type'])) ? $data['payment_type'] : null;
        $validate = 'payment';

        switch ($paymentType) {
            case "credit_card_internal":
                if (isset($data['cc_brand']) && isset($data['cc_number'])) {
                    $data['payment_info_1'] = sprintf("%s:%s", $data['cc_brand'], $data['cc_number']);
                }
                if (isset($data['cc_holder_name'])) {
                    $data['payment_info_2'] = $data['cc_holder_name'];
                }
                if (isset($data['cc_expires_at'])) {
                    $data['payment_info_3'] = $data['cc_expires_at'];
                }

                $validate = 'paymentCreditCardInternal';
                break;

        }

        $order = $this->getOrder();
        $order->accessible(array_keys($data), true);
        $order = $this->ShopOrders->patchEntity($order, $data, ['validate' => $validate]);
        $this->setOrder($order, true);
    }

    public function setShippingType($type = null)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order shipping type: No order');
            return false;
        }

        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');

        $order = $this->getOrder();
        $order->accessible('shipping_type', true);
        $order = $this->ShopOrders->patchEntity($order, ['shipping_type' => $type], ['validate' => false]);
        $this->setOrder($order, true);
    }


}