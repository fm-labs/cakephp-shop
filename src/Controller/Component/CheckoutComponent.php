<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Core\App;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Shop\Core\Checkout\CheckoutStepInterface;
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
     * @var array
     */
    public $steps = [
        //'Shop.Cart',
        //'Shop.Customer',
        'Shop.Billing',
        'Shop.Shipping',
        'Shop.Payment',
        'Shop.Review',
    ];

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * @var CheckoutStepInterface[]
     */
    protected $_stepRegistry = [];

    public function initialize(array $config)
    {
        $this->ShopOrders = $this->_registry->getController()->loadModel('Shop.ShopOrders');
        //$this->ShopOrders = TableRegistry::get('Shop.ShopOrders');

        foreach ($this->steps as $stepClass) {
            $class = App::className($stepClass, 'Core/Checkout/Step', 'Step');
            if (!$class) {
                throw new \InvalidArgumentException('Checkout step class not found: ' . $stepClass);
            }

            $step = new $class($this);
            if (!($step instanceof CheckoutStepInterface)) {
                throw new \InvalidArgumentException('Checkout step ' . $class . ' is not an instance of CheckoutStepInterface');
            }

            $stepId = $step->getId();
            $this->_stepRegistry[$stepId] = $step;
        }
    }

    public function beforeFilter()
    {
    }

    public function beforeRender(Event $event)
    {
        //$event->subject()->set('order', $this->getOrder());
    }

    public function describeSteps()
    {
        $steps = [];
        foreach ($this->_stepRegistry as $stepId => $step) {
            $steps[$stepId] = [
                'action' => $stepId,
                'title' => $step->getTitle(),
                'is_complete' => ($this->getOrder()) ? $step->isComplete() : false,
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
        if (!isset($this->_stepRegistry[$stepId])) {
            throw new \InvalidArgumentException('Step ' . $stepId . ' is not registered');
        }

        return $this->_stepRegistry[$stepId];
    }

    /**
     * Checks if previous steps have been completed
     * @param $stepId
     * @return bool
     */
    public function checkStep($stepId)
    {
        $complete = true;
        foreach ($this->_stepRegistry as $_stepId => $step) {
            if ($stepId == $_stepId) {
                break;
            }
            $complete = $complete && $step->isComplete();
        }
        return $complete;
    }

    /**
     * @return CheckoutStepInterface
     */
    public function nextStep()
    {
        foreach ($this->_stepRegistry as $stepId => $step) {
            if (!$step->isComplete()) {
                return $step;
            }
        }
    }

    /**
     * @return \Cake\Network\Response|null
     */
    public function redirectNext()
    {
        $step = $this->nextStep();
        if ($step) {
            $redirect = $step->getUrl();
        } else {
            $redirect = ['action' => 'index'];
        }
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

    /**
     * @param ShopOrder $order
     * @deprecated Use CartComponent->setOrder() directly instead.
     */
    public function setOrder(ShopOrder $order)
    {
        $this->Cart->setOrder($order);
    }

    /**
     * @param ShopAddress $address
     * @return $this
     */
    public function setBillingAddress(ShopOrderAddress $address)
    {
        //$this->billingAddress = $address;
        //$this->_patchOrderBillingAddress($this->_prefixAddress($address->toArray(), 'billing'));

        $address->type = 'B';
        $address->shop_order_id = $this->getOrder()->id;
        if (!$this->ShopOrders->OrderAddresses->save($address)) {
            //throw new \Exception('Checkout:setBillingAddress failed');
            return false;
        }

        if (!$this->getOrder()->getShippingAddress()) {
            $order = $this->ShopOrders->patchEntity($this->getOrder(), ['shipping_use_billing' => true]);
            if (!$this->ShopOrders->save($order)) {
                //throw new \Exception('Checkout: Order update failed');
                return false;
            }
        }

        return true;
    }

    public function setShippingAddress(ShopOrderAddress $address)
    {
        //$this->shippingAddress = $address;
        //$this->_patchOrderShippingAddress($this->_prefixAddress($address->toArray(), 'shipping'));

        $address->type = 'S';
        $address->shop_order_id = $this->getOrder()->id;
        if (!$this->ShopOrders->OrderAddresses->save($address)) {
            //throw new \Exception('Checkout:setShippingAddress failed');
            return false;
        }

        $order = $this->ShopOrders->patchEntity($this->getOrder(), ['shipping_use_billing' => false]);
        if (!$this->ShopOrders->save($order)) {
            //throw new \Exception('Checkout: Order update failed');
            return false;
        }

        return true;
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
        $this->setOrder($order);
    }

    public function setShippingType($type = null)
    {
        $this->_patchOrderShippingType($type);
    }

    public function submitOrder(array $data = [])
    {

        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to submit order: No order');
            return false;
        }

        if ($this->Shop->getCustomer()) {
            $data['shop_customer_id'] = $this->Shop->getCustomer()['id']; //@TODO Check if customer_id has already been set upon creation
            $data['customer_email'] = $this->Shop->getCustomer()['email']; //@TODO This can be ommited, as we already know the customerId
        }

        $order = $this->ShopOrders->patchEntity($this->getOrder(), $data, ['validate' => 'submit']);
        if ($order->errors()) {
            return false;
        }

        return $this->ShopOrders->submit($order);
    }

    protected function _writeSession()
    {
    }

    protected function _patchOrderShippingType($type)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order shipping type: No order');
            return false;
        }

        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');

        $order = $this->getOrder();
        $order->accessible('shipping_type', true);
        $order = $this->ShopOrders->patchEntity($order, ['shipping_type' => $type], ['validate' => false]);
        $this->setOrder($order);
    }

    protected function _patchOrderBillingAddress(array $data)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order billing: No order');
            return false;
        }

        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
        $order = $this->ShopOrders->patchEntity($this->getOrder(), $data, ['validate' => 'billing']);
        $this->setOrder($order);
    }

    protected function _patchOrderShippingAddress(array $data)
    {
        if (!$this->getOrder()) {
            Log::warning('Checkout: Failed to patch order shipping: No order');
            return false;
        }

        $validate = 'shipping';
        if (isset($data['shipping_use_billing']) && $data['shipping_use_billing'] == true) {
            $validate = 'billing';
        }

        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
        $this->ShopOrders->validator()->notEmpty('shipping_type');


        $order = $this->ShopOrders->patchEntity($this->getOrder(), $data, ['validate' => $validate]);
        $this->setOrder($order);
    }

    protected function _prefixAddress($address, $scope = 'billing')
    {
        $addr = [];

        $fields = [
            'first_name',
            'last_name',
            'name',
            'is_company',
            'street',
            'taxid',
            'zipcode',
            'city',
            'country',
        ];

        if (isset($address['id'])) {
            $_idKey = $scope . '_address_id';
            $addr[$_idKey] = $address['id'];
        }

        array_walk($address, function($val, $key) use (&$addr, $fields, $scope) {

            if (!in_array($key, $fields)) return;

            $_key = $scope . '_' . $key;
            $addr[$_key] = $val;
        });
        return $addr;
    }

}