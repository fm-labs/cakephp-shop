<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 14.12.15
 * Time: 15:51
 */

namespace Shop\Lib;


use Cake\Core\Exception\Exception;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManagerTrait;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Shop\Event\ShopEventListener;
use Shop\Model\Entity\ShopCustomer;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Table\ShopAddressesTable;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class LibShopCart
 * @package Shop\Lib
 *
 * @property ShopOrdersTable $ShopOrders
 * @property ShopProductsTable $ShopProducts
 */
class LibShopCart
{

    use EventDispatcherTrait;

    /**
     * @var ShopCustomer
     */
    public $customer;

    /**
     * @var ShopOrder
     */
    public $order;

    /**
     * @var string
     */
    public $sessionId;

    /**
     * @var string uuid
     */
    public $cartId;

    public function __construct($sessionId, $cartId)
    {
        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');

        $this->eventManager()->on(new ShopEventListener());

        $this->sessionId = $sessionId;
        $this->cartId = $cartId;

        $this->getOrder();
    }

    public function reset()
    {
        $this->cartId = null;
        $this->order = null;
    }

    protected function _createOrder()
    {
        if (!$this->cartId) {
            $this->cartId = Text::uuid();
            debug("generated cartId " . $this->cartId);
        }
        $order = $this->ShopOrders->newEntity([
            'sessionid' => $this->sessionId,
            'cartid' => $this->cartId,
            'is_temporary' => true
        ]);
        if ($order->errors() || !$this->ShopOrders->save($order)) {
            debug($order->errors());
            throw new Exception('Fatal error: Failed to create cart');
        }
        Log::debug("created cart order with id " . $order->id . " cartId: " . $order->cartid);

        return $this->ShopOrders
            ->find()
            ->where([
                'ShopOrders.id' => $order->id,
            ])
            ->contain(['ShopOrderItems', 'ShopCustomers'])
            ->first();
    }

    /**
     * @return \Cake\Datasource\EntityInterface|\Cake\ORM\Entity|ShopOrder
     */
    public function getOrder($create = false, $force = false)
    {
        if (!$this->order || $force) {

            $order = $this->ShopOrders
                ->find()
                ->where([
                    //'sessionid' => $this->sessionId,
                    'cartid' => $this->cartId,
                    'is_temporary' => true,
                ])
                ->contain(['ShopOrderItems', 'ShopCustomers'])
                ->first();

            //debug("No order found with cartId " . $this->cartId);

            if (!$order && $create) {
                $order = $this->_createOrder();

                //debug("created order " . $order->id);
            }

            if (!$order) {
                return null;
            }

            $this->order = $order;
            $this->customer = $order->shop_customer;
        }

        return $this->order;
    }


    public function setCustomer(ShopCustomer $customer)
    {
        $this->getOrder(true);
        $this->order->shop_customer_id = $customer->id;

        /*
        $this->order->billing_first_name = ($this->order->billing_first_name) ?: $customer->first_name;
        $this->order->billing_last_name = ($this->order->billing_last_name) ?: $customer->last_name;
        $this->order->billing_street = ($this->order->billing_street) ?: $customer->street;
        $this->order->billing_zipcode = ($this->order->billing_zipcode) ?: $customer->zipcode;
        $this->order->billing_city = ($this->order->billing_city) ?: $customer->city;
        $this->order->billing_country = ($this->order->billing_country) ?: $customer->country;
        */

        if ($this->saveOrder()) {
            $this->customer = $customer;
            $this->getOrder(false, true);
            return true;
        }

        return false;
    }

    public function resetCustomer()
    {
        if ($this->order) {
            $this->order->shop_customer_id = null;

            $this->order->billing_first_name = null;
            $this->order->billing_last_name = null;
            $this->order->billing_street = null;
            $this->order->billing_zipcode = null;
            $this->order->billing_city = null;
            $this->order->billing_country = null;

            //$this->order->shipping_use_billing = true;
            $this->order->shipping_first_name = null;
            $this->order->shipping_last_name = null;
            $this->order->shipping_street = null;
            $this->order->shipping_zipcode = null;
            $this->order->shipping_city = null;
            $this->order->shipping_country = null;
            $this->saveOrder();

            if (!$this->saveOrder()) {
                return false;
            }
        }

        $this->customer = null;
        return true;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function refresh()
    {
        if (!$this->order) {
            return false;
        }

        //$this->ShopOrders->calculate($this->order->id);
        $this->order->update();
        return $this->saveOrder();
    }

    public function toArray()
    {
        return [
            'sessionId' => $this->sessionId,
            'cartId' => $this->cartId,
            'Order' => ($this->order) ? $this->order->toArray() : null,
            'Customer' => ($this->customer) ? $this->customer->toArray() : null
        ];
    }

    public function saveOrder()
    {
        if (!$this->order) {
            return false;
        }

        $saved = $this->ShopOrders->save($this->order);
        if (!$saved) {
            debug("FAiled to save order");
            return false;
        }

        return $this->order = $saved;
    }

    public function submitOrder(array $data)
    {
        if (!$this->order) {
            return false;
        }

        if ($this->order->status > 0) {
            throw new \Exception("Order already submitted");
        }

        $data['uuid'] = Text::uuid();
        $data['submitted'] = Time::now();
        $data['is_temporary'] = false;
        $data['status'] = 1;
        $this->order = $this->ShopOrders->patchEntity($this->order, $data, ['validate' => 'submit']);
        if ($this->order->errors()) {
            return false;
        }

        return $this->ShopOrders->submit($this->order);

    }

    public function setBillingAddressById($addressId)
    {

        $address = $this->ShopOrders->BillingAddresses
            ->find()
            ->where([
                'id' => $addressId,
                //'type' => ShopAddressesTable::TYPE_BILLING,
                'shop_customer_id' => $this->order->shop_customer_id
            ])
            ->first();

        if (!$address) {
            debug('address not found for customer with id ' . $this->order->shop_customer_id);
            return false;
        }

        $this->patchOrderBilling($this->_prefixAddress($address->toArray(), 'billing'));

        if (!$this->order->shipping_address_id) {
            $this->order->shipping_use_billing = true;
            $this->patchOrderShipping($this->_prefixAddress($address->toArray(), 'shipping'));
        }

        return $this->saveOrder();
    }

    public function setShippingAddressById($addressId)
    {
        $address = $this->ShopOrders->BillingAddresses
            ->find()
            ->where([
                'id' => $addressId,
                //'type' => ShopAddressesTable::TYPE_SHIPPING,
                'shop_customer_id' => $this->order->shop_customer_id
            ])
            ->first();

        if (!$address) {
            debug('address not found for customer with id ' . $this->order->shop_customer_id);
            return false;
        }

        $shippingAddr = $this->_prefixAddress($address->toArray(), 'shipping');
        $shippingAddr['shipping_use_billing'] = false;

        $this->patchOrderShipping($shippingAddr);
        return $this->saveOrder();
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

        $_idKey = $scope . '_address_id';
        $addr[$_idKey] = $address['id'];

        array_walk($address, function($val, $key) use (&$addr, $fields, $scope) {

            if (!in_array($key, $fields)) return;

           $_key = $scope . '_' . $key;
            $addr[$_key] = $val;
        });
        return $addr;
    }

    public function patchOrderBilling(array $data)
    {
        if (!$this->order) {
            return false;
        }

        $this->order = $this->ShopOrders->patchEntity($this->order, $data, ['validate' => 'billing']);
    }

    public function patchOrderShipping(array $data)
    {
        if (!$this->order) {
            return false;
        }

        $validate = 'shipping';


        if (isset($data['shipping_use_billing']) && $data['shipping_use_billing'] == true) {
            $validate = 'billing';
        }

        $this->ShopOrders->validator()->notEmpty('shipping_type');

        $this->order = $this->ShopOrders->patchEntity($this->order, $data, ['validate' => $validate]);
    }

    public function patchOrderPayment(array $data)
    {
        if (!$this->order) {
            return false;
        }

        $paymentType = (isset($data['payment_type'])) ? $data['payment_type'] : null;
        $validate = 'payment';

        switch ($paymentType) {
            case "credit_card_internal":
                $validate = 'paymentCreditCardInternal';
                break;

        }

        $this->order->accessible('*', true);
        $this->order = $this->ShopOrders->patchEntity($this->order, $data, ['validate' => $validate]);
    }


    public function getOrderItemsCount()
    {
        if (!$this->order) {
            return false;
        }

        return count($this->order->shop_order_items);
    }

    public function addItem($refid = null, $amount = 1)
    {
        $this->getOrder(true);
        $item = null;
        foreach ($this->order->shop_order_items as $_item) {
            if ($_item->refid == $refid) {
                $item = $_item;
                break;
            }
        }

        if ($item && $amount == 0) {
            //return $this->removeItem($item->shop_order_id, $item->id);
        } elseif ($amount < 0) {
            $amount = abs($amount);
        }

        if (!$item) {

            $this->ShopProducts = TableRegistry::get('Shop.ShopProducts');
            $product = $this->ShopProducts->get($refid);


            $item = $this->ShopOrders->ShopOrderItems->newEntity([
                'refscope' => 'Shop.ShopProducts',
                'refid' => $refid,
                'shop_order_id' => $this->order->id,
                'amount' => $amount,
                'title' => $product->title,
                'unit' => ($product->unit) ?: 'x',
                'item_value_net' => $product->price_net,
                'tax_rate' => $product->tax_rate
            ]);
        } else {
            $item->amount += $amount;
        }

        $item->calculate();

        if (!$this->ShopOrders->ShopOrderItems->save($item)) {
            debug($item->errors());
            Log::debug('Failed to add order item to order with ID ' . $this->order->id);
            return false;
        }

        Log::debug('Added order item to order with ID ' . $this->order->id);
        return true;
    }

    public function updateItem($orderItemId, $data = [])
    {
        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);
        $orderItem->accessible('*', false);
        $orderItem->accessible('amount', true);
        $this->ShopOrders->ShopOrderItems->patchEntity($orderItem, $data);
        return $this->ShopOrders->ShopOrderItems->save($orderItem);
    }
}