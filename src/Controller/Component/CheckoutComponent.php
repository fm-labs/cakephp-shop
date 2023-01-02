<?php
declare(strict_types=1);

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotImplementedException;
use Cake\Http\Response;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Checkout\CheckoutStepRegistry;
use Shop\Core\Checkout\Step\SubmitStep;
use Shop\Event\CheckoutEvent;
use Shop\Exception\CheckoutException;
use Shop\Lib\Shop;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderAddress;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutComponent
 *
 * @package Shop\Controller\Component
 * @property \Shop\Controller\Component\ShopComponent $Shop
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class CheckoutComponent extends Component
{
    /**
     * @var array
     */
    public $components = ['Shop.Shop'];

    /**
     * @var \Shop\Model\Table\ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * @var \Shop\Model\Entity\ShopOrder
     */
    protected $_order;

    /**
     * @var \Shop\Core\Checkout\CheckoutStepRegistry
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
     * @var \Shop\Core\Checkout\CheckoutStepInterface
     */
    protected $_activeStep;

    /**
     * @param array $config
     */
    public function initialize(array $config): void
    {
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->ShopOrders->getEventManager()->on($this);
        $this->_stepRegistry = new CheckoutStepRegistry($this);

        $steps = $config['steps'] ?? [];
        $steps = $steps ?: (array)Shop::config('Shop.Checkout.Steps');

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

        if ($this->_registry->has('Auth')) {
            $this->_registry->get('Auth')->allow($this->_stepRegistry->loaded());
        }
        elseif ($this->_registry->has('Authentication')) {
            $this->_registry->get('Authentication')->allowUnauthenticated($this->_stepRegistry->loaded());
        }
    }

    /**
     * Startup event
     * @param \Cake\Event\Event $event
     */
    public function startup(\Cake\Event\EventInterface $event)
    {
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        $event->getSubject()->set('order', $this->_order);
        $event->getSubject()->set('step', $this->_activeStep);
        $event->getSubject()->set('stepId', $this->_active);
    }

    /**
     * @param $cartId
     */
    public function initFromCartId($cartId)
    {
        if (!$cartId) {
            throw new \InvalidArgumentException("Checkout: Unable to init from cart: Cart ID missing");
        }
        $this->_order = $this->ShopOrders->find('cart', ['ShopOrders.cartid' => $cartId])->first();
    }

    /**
     * @param \Cake\Event\Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeStep(Event $event)
    {
        // check if order is ready for checkout
        if (!$this->getOrder() || count($this->getOrder()->shop_order_items) < 1) {
            $event->getSubject()->Flash->error(__d('shop', 'Checkout aborted: Your cart is empty'));

            return $this->getController()->redirect(['_name' => 'shop:cart']);
        }

        if ($this->getOrder()->is_temporary === false || $this->getOrder()->status > ShopOrdersTable::ORDER_STATUS_TEMP) {
            return $this->getController()->redirect(['controller' => 'Orders', 'action' => 'view', $this->getOrder()->uuid]);
        }
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function afterStep(Event $event)
    {
        $order = null;
        if ($this->_order) {
            $order = $this->_order->toArray();
        }
        $this->getController()->getRequest()->getSession()->write('Shop.Order', $order);
    }

    /**
     * @param $stepId
     * @return bool
     */
    public function hasStep($stepId): bool {
        return $this->_stepRegistry->has($stepId);
    }

    /**
     * @param $stepId
     * @return \Cake\Http\Response|null
     * @deprecated Use execute() instead
     */
    public function executeStep($stepId)
    {
        return $this->execute($stepId);
    }

    /**
     * @param null|string $stepId
     * @return \Cake\Http\Response|null
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
     * @return \Shop\Core\Checkout\CheckoutStepInterface
     */
    public function getStep($stepId)
    {
        if (!$this->_stepRegistry->has($stepId)) {
            throw new \InvalidArgumentException('Step ' . $stepId . ' is not registered');
        }

        return $this->_stepRegistry->get($stepId);
    }

    /**
     * @return \Shop\Core\Checkout\CheckoutStepInterface
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
     * @param \Shop\Core\Checkout\CheckoutStepInterface $step
     * @return null|\Cake\Http\Response
     */
    protected function _executeStep(CheckoutStepInterface $step)
    {
        // set active step and store session
        $this->_activeStep = $step;
        $this->getController()->getRequest()->getSession()->write('Shop.Checkout.Step', $this->_activeStep->toArray());

        // before step
        $event = $this->getController()->getEventManager()->dispatch(new CheckoutEvent('Shop.Checkout.beforeStep', $this, compact('step')));
        if ($event->getResult() instanceof Response) {
            return $event->getResult();
        } elseif ($event->getResult() instanceof CheckoutStepInterface) {
            $step = $event->getResult();
        }

        // execute
        $response = $step->execute($this->getController());

        // after step
        $event = $this->getController()->getEventManager()->dispatch(new CheckoutEvent('Shop.Checkout.afterStep', $this, ['step' => $this->_activeStep]));
        if ($event->getResult() instanceof Response) {
            return $event->getResult();
        }

        return $response;
    }

    /**
     * @param string|\Shop\Core\Checkout\CheckoutStepInterface $stepId
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
     * @return \Cake\Http\Response|null
     */
    public function redirectNext()
    {
        $step = $this->nextStep();
        if ($step) {
            return $this->getController()->redirect($this->buildStepUrl($step));
        }
        return null;
    }

    /**
     * Get cart order.
     *
     * @return \Shop\Model\Entity\ShopOrder
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
     * @param \Shop\Model\Entity\ShopOrder $order
     * @param bool $update
     * @return $this
     */
    public function setOrder(ShopOrder $order, $update = true)
    {
        $this->_order = $order;
        if ($update) {
            if (!$this->saveOrder()) {

            }
        }

        return $this;
    }

    /**
     * @return \Cake\Datasource\EntityInterface
     * @throws \Shop\Exception\CheckoutException
     * @throws \Cake\ORM\Exception\PersistenceFailedException
     */
    public function saveOrder()
    {
        $order = $this->getOrder();
        if (!$order) {
            throw new CheckoutException("No order found");
        }
        return $this->ShopOrders->saveOrFail($order);
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
     * @return bool|\Cake\Datasource\EntityInterface|mixed|\Shop\Model\Entity\ShopOrder
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
     * @param \Cake\Event\Event $event
     */
    public function afterSubmit(Event $event)
    {
        $this->_order = null;

        $this->getController()->getRequest()->getSession()->delete('Shop.Cart');
        $this->getController()->getRequest()->getSession()->delete('Shop.Checkout');
        $this->getController()->getRequest()->getSession()->delete('Shop.Order');
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
     * @param \Shop\Model\Entity\ShopOrderAddress $address
     * @return bool|\Cake\Datasource\EntityInterface|\Shop\Model\Entity\ShopOrderAddress
     */
    public function setBillingAddress(ShopOrderAddress $address)
    {
        return $this->ShopOrders->setOrderAddress($this->getOrder(), $address, 'B');
    }

    /**
     * Set order shipping address.
     *
     * @param \Shop\Model\Entity\ShopOrderAddress $address
     * @return bool|\Cake\Datasource\EntityInterface|\Shop\Model\Entity\ShopOrderAddress
     */
    public function setShippingAddress(ShopOrderAddress $address)
    {
        return $this->ShopOrders->setOrderAddress($this->getOrder(), $address, 'S');
    }

    /**
     * @param $type
     * @param array $data
     * @return bool|\Shop\Controller\Component\CheckoutComponent
     */
    public function setPaymentType($type, array $data)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order shipping type: No order');

            return false;
        }

        $paymentType = $data['payment_type'] ?? null;
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
        $order->setAccess(array_keys($data), true);
        $order = $this->ShopOrders->patchEntity($order, $data, ['validate' => $validate]);
        return $this->setOrder($order, true);
    }

    /**
     * @param null $type
     * @return bool|\Shop\Controller\Component\CheckoutComponent
     */
    public function setShippingType($type = null)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order shipping type: No order');

            return false;
        }

        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');

        $order = $this->getOrder();
        $order->setAccess('shipping_type', true);
        $order = $this->ShopOrders->patchEntity($order, ['shipping_type' => $type], ['validate' => false]);

        return $this->setOrder($order, true);
    }

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        $events = parent::implementedEvents();

        $events['Shop.Checkout.beforeStep'] = ['callable' => 'beforeStep'];
        $events['Shop.Checkout.afterStep'] = ['callable' => 'afterStep'];
        $events['Shop.Model.Order.afterSubmit'] = ['callable' => 'afterSubmit', 'priority' => 90];

        return $events;
    }
}
