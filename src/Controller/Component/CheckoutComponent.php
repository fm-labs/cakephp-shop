<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Core\App;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Shop\Checkout\CheckoutStepInterface;
use Shop\Model\Entity\ShopAddress;
use Shop\Model\Entity\ShopCustomer;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutComponent
 * @package Shop\Controller\Component
 * @property ShopComponent $Shop
 * @property CartComponent $Cart
 */
class CheckoutComponent extends Component
{
    public $components = ['Shop.Shop', 'Shop.Cart'];

    public $steps = [
        'Shop.Cart',
        'Shop.Customer',
        'Shop.Billing',
        'Shop.Shipping',
        'Shop.Payment',
        'Shop.Review'
    ];

    public $billingAddress;
    public $shippingAddress;

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    protected $_stepRegistry = [];
    protected $_stepUrls = [];

    public function initialize(array $config)
    {
        $this->ShopOrders = $this->_registry->getController()->loadModel('Shop.ShopOrders');

        foreach ($this->steps as $stepClass) {
            $class = App::className($stepClass, 'Checkout/Step', 'Step');
            if (!$class) {
                throw new \InvalidArgumentException('Checkout step class not found: ' . $stepClass);
            }

            $step = new $class($this);
            if (!($step instanceof CheckoutStepInterface)) {
                throw new \InvalidArgumentException('Checkout step ' . $class . ' is not an instance of CheckoutStepInterface');
            }

            $stepId = $step->getId();
            $this->_stepRegistry[$stepId] = $step;
            $this->_stepUrls[$stepId] = $step->getUrl();
        }
    }

    public function beforeFilter()
    {
        $this->billingAddress = $this->request->session()->read('Shop.BillingAddress');
        $this->shippingAddress = $this->request->session()->read('Shop.ShippingAddress');
    }

    public function beforeRender()
    {
        $this->_writeSession();
    }

    public function getStepList()
    {
        return $this->_stepUrls;
    }

    public function getStep($stepId)
    {
        if (!isset($this->_stepRegistry[$stepId])) {
            throw new \InvalidArgumentException('Step ' . $stepId . ' is not registered');
        }

        return $this->_stepRegistry[$stepId];
    }

    public function nextStep()
    {
        foreach ($this->_stepRegistry as $stepId => $step) {
            if (!$step->isComplete()) {
                return $step;
            }
        }
    }

    public function redirectNext()
    {
        $step = $this->nextStep();
        if ($step) {
            $redirect = $step->getUrl();
        } else {
            $redirect = ['action' => 'index'];
        }
        $this->_registry->getController()->redirect($redirect);
    }

    public function reset()
    {
        $this->billingAddress = null;
        $this->shippingAddress = null;
        $this->_writeSession();
    }

    public function getOrder()
    {
        return $this->Cart->getOrder();
    }

    public function setOrder(ShopOrder $order)
    {
        $this->Cart->setOrder($order);
    }

    /**
     * @param ShopAddress $address
     * @return $this
     */
    public function setBillingAddress(ShopAddress $address)
    {
        $this->billingAddress = $address;

        $this->_patchOrderBillingAddress($this->_prefixAddress($address->toArray(), 'billing'));

        if (!$this->shippingAddress) {
            $this->setShippingAddress($address);
        }
        return $this;
    }

    public function setShippingAddress(ShopAddress $address)
    {
        $this->shippingAddress = $address;

        $this->_patchOrderShippingAddress($this->_prefixAddress($address->toArray(), 'shipping'));

        return $this;
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

        $data['customer_email'] = $this->Shop->getCustomer()->email;

        $order = $this->ShopOrders->patchEntity($this->getOrder(), $data, ['validate' => 'submit']);
        if ($order->errors()) {
            return false;
        }

        return $this->ShopOrders->submit($order);
    }

    protected function _writeSession()
    {
        $this->request->session()->write('Shop.BillingAddress', $this->billingAddress);
        $this->request->session()->write('Shop.ShippingAddress', $this->shippingAddress);
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