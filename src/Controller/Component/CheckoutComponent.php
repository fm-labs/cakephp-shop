<?php

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotImplementedException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Checkout\CheckoutStepRegistry;
use Shop\Core\Checkout\Step\SubmitStep;
use Shop\Event\CheckoutEvent;
use Shop\Lib\Shop;
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
        $steps = ($steps) ?: (array)Shop::config('Shop.Checkout.Steps');

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

        $this->getController()->Auth->allow($this->_stepRegistry->loaded());
    }

    /**
     * Startup event
     * @param Event $event
     */
    public function startup(Event $event)
    {
    }

    /**
     * @param Event $event
     */
    public function beforeRender(Event $event)
    {
        $event->subject()->set('order', $this->_order);
        $event->subject()->set('step', $this->_activeStep);
        $event->subject()->set('stepId', $this->_active);
    }

    /**
     * @return \Cake\Controller\Controller
     */
    public function getController()
    {
        return $this->_registry->getController();
    }

    /**
     * @param $cartId
     */
    public function initFromCartId($cartId)
    {
        if (!$cartId) {
            throw new \InvalidArgumentException("Checkout: Unable to init from cart: Cart ID missing");
        }
        $this->_order = $this->ShopOrders->find('cart', ['ShopOrders.cartid' => $cartId]);
    }

    /**
     * @param Event $event
     * @return Response|null
     */
    public function beforeStep(Event $event)
    {
        // check if order is ready for checkout
        if (!$this->getOrder() || count($this->getOrder()->shop_order_items) < 1) {
            $event->subject()->Flash->error(__d('shop', 'Checkout aborted: Your cart is empty'));

            return $this->getController()->redirect(['_name' => 'shop:cart']);
        }

        if ($this->getOrder()->is_temporary === false || $this->getOrder()->status > ShopOrdersTable::ORDER_STATUS_TEMP) {
            return $this->getController()->redirect(['controller' => 'Orders', 'action' => 'view', $this->getOrder()->uuid]);
        }
    }

    /**
     * @param Event $event
     */
    public function afterStep(Event $event)
    {
        $this->request->session()->write('Shop.Order', $this->_order->toArray());
    }

    /**
     * @param $stepId
     * @return Response|null
     * @deprecated Use execute() instead
     */
    public function executeStep($stepId)
    {
        return $this->execute($stepId);
    }

    /**
     * @param null|string $stepId
     * @return Response|null
     */
    public function execute($stepId = null)
    {
        $response = null;
        foreach ($this->_stepRegistry as $step) {
            // break at selected step
            if ($stepId && $step->getId() === $stepId) {
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
     * @return CheckoutStepInterface
     */
    public function nextStep()
    {
        foreach ($this->_stepRegistry as $step) {
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
        // set active step and store session
        $this->_activeStep = $step;
        $this->request->session()->write('Shop.Checkout.Step', $this->_activeStep->toArray());

        // before step
        $event = $this->getController()->eventManager()->dispatch(new CheckoutEvent('Shop.Checkout.beforeStep', $this, compact('step')));
        if ($event->result instanceof Response) {
            return $event->result;
        } elseif ($event->result instanceof CheckoutStepInterface) {
            $step = $event->result;
        }

        // execute
        $response = $step->execute($this->_registry->getController());

        // after step
        $event = $this->getController()->eventManager()->dispatch(new CheckoutEvent('Shop.Checkout.afterStep', $this, ['step' => $this->_activeStep]));
        if ($event->result instanceof Response) {
            return $event->result;
        }

        return $response;
    }

    /**
     * @param string|CheckoutStepInterface $stepId
     * @return array
     */
    public function buildStepUrl($stepId)
    {
        if ($stepId instanceof CheckoutStepInterface) {
            $stepId = $stepId->getId();
        }

        return ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => $stepId, $this->getOrder()->cartid];
    }

    /**
     * Redirect to next step
     *
     * @return \Cake\Network\Response|null
     */
    public function redirectNext()
    {
        $step = $this->nextStep();
        if ($step) {
            return $this->_registry->getController()->redirect($this->buildStepUrl($step));
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

    /**
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function saveOrder()
    {
        return $this->ShopOrders->save($this->getOrder());
    }

    /**
     * @return $this
     */
    public function reloadOrder()
    {
        $this->initFromCartId($this->getOrder()->cartid);

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

        if (!($this->nextStep() instanceof SubmitStep)) { //@TODO Replace this workaround: Check if all previously steps have been completed
            return $this->redirectNext();
        }

        return $this->ShopOrders->submitOrder($this->getOrder(), $data);
    }

    /**
     * @param Event $event
     */
    public function afterSubmit(Event $event)
    {
        $this->_order = null;

        $this->request->session()->delete('Shop.Cart');
        $this->request->session()->delete('Shop.Checkout');
        $this->request->session()->delete('Shop.Order');
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

    /**
     * @param $type
     * @param array $data
     * @return bool|CheckoutComponent
     */
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

    /**
     * @param null $type
     * @return bool|CheckoutComponent
     */
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
     * @return array
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();

        $events['Shop.Checkout.beforeStep'] = ['callable' => 'beforeStep'];
        $events['Shop.Checkout.afterStep'] = ['callable' => 'afterStep'];
        $events['Shop.Model.Order.afterSubmit'] = ['callable' => 'afterSubmit', 'priority' => 90];

        return $events;
    }
}
