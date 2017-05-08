<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Core\App;
use Cake\Core\StaticConfigTrait;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Network\Exception\InternalErrorException;
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
     * @var CheckoutStepRegistry
     */
    protected $_stepRegistry;

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    protected $_steps = [];

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

    public function getController()
    {
        return $this->_registry->getController();
    }

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
     * Checks if previous steps have been completed
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
    public function nextStep()
    {
        foreach ($this->_steps as $stepId => $step) {
            if (!$this->getStep($stepId)->isComplete()) {
                //debug("step not complete: " . $stepId);
                return $this->getStep($stepId);
            }
            //debug("step complete: " . $stepId);
        }
    }

    public function executeStep(CheckoutStepInterface $step)
    {
        $this->request->session()->write('Shop.Checkout.Step', $step->toArray());
        return $step->execute($this->_registry->getController());
    }

    public function redirectUrl()
    {
        $step = $this->nextStep();
        return ($step) ? $step->getUrl() : null;
    }

    /**
     * @return \Cake\Network\Response|null
     */
    public function redirectNext()
    {
        $redirect = $this->redirectUrl();
        $redirect = ($redirect) ?: ['controller' => 'Checkout', 'action' => 'index'];
        return $this->_registry->getController()->redirect($redirect);
    }

    public function reset()
    {
        if (!$this->getOrder()) {
            return false;
        }

        $order = $this->getOrder();
        $order->shipping_type = null;
        $order->shipping_use_billing = false;
        $order->payment_type = null;
        $order->payment_info_1 = null;
        $order->payment_info_2 = null;
        $order->payment_info_3 = null;
        $this->setOrder($order);
    }

    public function getOrder()
    {
        return $this->Cart->getOrder();
    }

    public function setOrder(ShopOrder $order)
    {
        $this->Cart->setOrder($order);
    }

    public function setBillingAddress(ShopOrderAddress $address)
    {
        return $this->ShopOrders->setOrderAddress($this->getOrder(), $address, 'B');
    }

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
        $order->accessible('*', true);
        $order = $this->ShopOrders->patchEntity($this->order, $data, ['validate' => $validate]);
        $this->ShopOrders->save($order);
        $this->setOrder($order);
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
        $this->ShopOrders->save($order);
        $this->setOrder($order);
    }

    public function submitOrder()
    {
        if (!$this->getOrder()) {
            return false;
        }
        return $this->ShopOrders->submitOrder($this->getOrder());
    }

}