<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Core\App;
use Cake\Core\StaticConfigTrait;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
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
class CheckoutComponent extends Component implements \Iterator, \SeekableIterator
{

    /**
     * @var array
     */
    public $components = ['Shop.Shop'];

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * @var ShopOrder
     */
    protected $_order;

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
     * @var string
     */
    protected $_active;

    /**
     * Active step.
     *
     * @var CheckoutStepInterface
     */
    protected $_activeStep;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        //$this->ShopOrders = $this->_registry->getController()->loadModel('Shop.ShopOrders');
        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
        $this->ShopOrders->eventManager()->on($this);
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
        $this->_active = key($steps);

    }

    public function beforeRender(Event $event)
    {
        $event->subject()->set('order', $this->_order);
    }

    /**
     * @return \Cake\Controller\Controller
     */
    public function getController()
    {
        return $this->_registry->getController();
    }

    public function initFromCartId($cartId)
    {
        if (!$cartId) {
            throw new \InvalidArgumentException("Checkout: Unable to init from cart: Cart ID missing");
        }
        $this->_order = $this->ShopOrders->find('cart', ['ShopOrders.cartid' => $cartId]);
    }

    public function executeStep($stepId)
    {
        /*
        $this->seek($stepId);
        //return $this->_executeStep($this->current());


        $response = $this->_executeStep($this->current());
        if (!($response instanceof Response)) {
            return $this->redirectNext();
        }
        return $response;
        */
        return $this->execute($stepId);
    }

    public function execute($stepId = null)
    {
        $response = null;

        foreach($this as $step) {

            // break at selected step
            if ($stepId && $this->key() === $stepId) {
                //debug("current " . $this->key() . " / " . $stepId);
                $response = $this->_executeStep($step);
                if ($response === true) {
                    return $this->redirectNext();
                }
                break;
            }

            // skip complete steps
            if ($step->isComplete()) {
                //debug($this->key() . " is complete ");
                continue;
            }

            //debug("executing " . $this->key() . " / " . $stepId);

            $response = $this->_executeStep($step);
            // continue to next step if execution was successful
            if ($response === true) {
                return $this->redirectNext();
            }

            // break at this step if execution failed
            if ($response === false) {
                break;
            }

            if ($response instanceof Response) {
                return $response;
            }
        }

        return $response;
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
                'is_complete' => $step->isComplete(),
                //'url' => $step->getUrl(),
                //'icon' => null
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
        $this->rewind();
        foreach ($this as $step) {
            if (!$step->isComplete()) {
                //debug("step not complete: " . $stepId);
                return $step;
            }
        }
    }

    /**
     * Execute step in controller context
     *
     * @param CheckoutStepInterface $step
     * @return null|\Cake\Network\Response
     */
    protected function _executeStep(CheckoutStepInterface $step)
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


        // after step
        $event = $this->getController()->eventManager()->dispatch(new Event('Shop.Checkout.afterStep', $this, ['step' => $this->_activeStep]));
        if ($event->result instanceof Response) {
            return $event->result;
        }

        return $response;
    }


    /**
     * Set the active step.
     *
     * @param CheckoutStepInterface $step
     */
    protected function _setActiveStep(CheckoutStepInterface $step)
    {
        $this->_activeStep = $step;
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
        return ($step) ? ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => $step->getId(), $this->getOrder()->cartid] : null;
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
        if ($redirect) {
            return $this->_registry->getController()->redirect($redirect);
        }
    }

    /**
     * Get cart order.
     *
     * @return ShopOrder
     */
    public function &getOrder()
    {
        if (!$this->_order) {
            throw new \RuntimeException("Checkout: Order not initialized");
        }
        return $this->_order;
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
        $this->_order = $order;
        if ($update) {
            return $this->saveOrder();
        }
        return $this->_order;
    }

    public function saveOrder()
    {
        return $this->ShopOrders->save($this->getOrder());
    }

    public function reloadOrder()
    {
        $cartId = $this->getOrder()->cartid;
        $this->initFromCartId($cartId);
        return $this;
    }

    /**
     * Submit order.
     *
     * @param array $data Additional submit data
     * @return bool|\Cake\Datasource\EntityInterface|mixed|ShopOrder
     * @throws \Exception
     */
    public function submitOrder(array $data = [])
    {
        if (!$this->getOrder()) {
            return false;
        }

        $nextStep = $this->nextStep();
        if ($nextStep->getId() != "review") {
            debug("next step is " . $nextStep->getId());
            return false;
        }

        return $this->ShopOrders->submitOrder($this->getOrder(), $data);
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
        return $this->setOrder($order, true);
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
        return $this->setOrder($order, true);
    }


    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return CheckoutStepInterface
     * @since 5.0.0
     */
    public function current()
    {
        return $this->_stepRegistry->get($this->_active);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->_active;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (!$this->_active) {
            return false;
        }

        if (!$this->_stepRegistry->has($this->_active)) {
            throw new \OutOfBoundsException("Step not loaded: " . $this->_active);
        }

        //if ($this->_stepRegistry->isComplete()) {
        //    throw new IncompleteStepException();
        //}
        return true;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->_steps);
        $this->_active = key($this->_steps);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $next = false; // flag indicating to exit loop on next iteration

        foreach (array_keys($this->_steps) as $stepId) {

            if ($next === true) {
                $this->_active = $stepId;
                return;
            }

            if ($this->_active == $stepId) {
                $next = true;
            }
        }

        // last step
        $this->_active = false;
    }

    /**
     * Seeks to a position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position <p>
     * The position to seek to.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function seek($position)
    {
        if (!$this->_stepRegistry->has($this->_active)) {
            throw new \OutOfBoundsException("Step not loaded: " . $this->_active);
        }

        $this->_active = $position;
    }

    public function implementedEvents()
    {
        $events = parent::implementedEvents();

        $events['Shop.Model.Order.afterSubmit'] = ['callable' => 'afterSubmit', 'priority' => 90];
        return $events;
    }

    public function afterSubmit(Event $event)
    {
        $this->_order = null;

        $this->request->session()->delete('Shop.Cart');
        $this->request->session()->delete('Shop.Checkout');
        $this->request->session()->delete('Shop.Order');

    }
}