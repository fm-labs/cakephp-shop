<?php
namespace Shop\Model\Table;

use Banana\Lib\Status;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use Shop\Core\Order\CostCalculator;
use Shop\Lib\Shop;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderAddress;
use Shop\Model\Entity\ShopOrderTransaction;

/**
 * ShopOrders Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopCustomers
 * @property \Cake\ORM\Association\BelongsTo $BillingAddresses
 * @property \Cake\ORM\Association\BelongsTo $ShippingAddresses
 * @property \Cake\ORM\Association\HasMany $ShopCarts
 * @property \Cake\ORM\Association\HasMany $ShopOrderItems
 * @property \Cake\ORM\Association\HasMany $ShopOrderAddresses
 */
class ShopOrdersTable extends Table
{

    const ORDER_STATUS_TEMP = 0; // Cart order
    const ORDER_STATUS_SUBMITTED = 1; // Order submitted (not payed yet)
    const ORDER_STATUS_PENDING = 2; // Waiting for payment
    const ORDER_STATUS_CONFIRMED = 3; // Payment provider confirmed payment
    const ORDER_STATUS_PAYED = 4; // Order is payed (We received the money)
    const ORDER_STATUS_DELIVERED = 5; // Order items have been delivered
    const ORDER_STATUS_CLOSED = 6; // Order is invoiced, payed and processed

    const ORDER_STATUS_STORNO = 80;
    const ORDER_STATUS_ERROR = 90;
    const ORDER_STATUS_ERROR_DELIVERY = 91;

    // unused
    const SHIPPING_STATUS_STANDBY = 0;
    const SHIPPING_STATUS_PENDING = 1;
    const SHIPPING_STATUS_DELIVERED = 10;
    const PAYMENT_STATUS_PENDING = 0;
    const PAYMENT_STATUS_PARTIAL = 1;
    const PAYMENT_STATUS_PAYED = 10;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('shop_orders');
        $this->setDisplayField('nr_formatted');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomers',
        ]);
        /*
        $this->hasMany('ShopCarts', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopCarts'
        ]);
        */
        $this->hasMany('ShopOrderItems', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderItems',
        ]);
        $this->hasMany('ShopOrderAddresses', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
            //'contain' => ['Countries']
        ]);
        $this->hasMany('ShopOrderTransactions', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderTransactions',
            //'contain' => ['Countries']
        ]);
        $this->hasOne('BillingAddresses', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
            'conditions' => ['BillingAddresses.type' => 'B'],
            'contain' => ['Countries'],
        ]);
        $this->hasOne('ShippingAddresses', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
            'conditions' => ['ShippingAddresses.type' => 'S'],
            'contain' => ['Countries'],
        ]);
        $this->hasMany('ShopOrderNotifications', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderNotifications',
        ]);
        $this->addBehavior('Banana.Statusable');

        if (Plugin::isLoaded('Search')) {
            // Add the behaviour to your table
            $this->addBehavior('Search.Search');

            // Setup search filter using search manager
            $this->searchManager()
                //->value('author_id')
                // Here we will alias the 'q' query param to search the `Articles.title`
                // field and the `Articles.content` field, using a LIKE match, with `%`
                // both before and after.
                ->add('nr', 'Search.Like', [
                    'before' => true,
                    'after' => true,
                    'fieldMode' => 'OR',
                    'comparison' => 'LIKE',
                    'wildcardAny' => '*',
                    'wildcardOne' => '?',
                    'field' => ['title'],
                ])
                ->value('shop_customer_id', [
                    'filterEmpty' => true,
                ])
                ->value('status', [
                    'filterEmpty' => true,
                ])
                ->value('payment_status', [
                    'filterEmpty' => true,
                ])
                ->value('shipping_status', [
                    'filterEmpty' => true,
                ])
                ->add('nr_formatted', 'Search.Callback', [
                    'callback' => function ($query, $args, $filter) {
                         return $query;
                    },
                ]);
        }
    }

    /**
     * @param Event $event
     * @param Validator $validator
     * @param $name
     */
    public function buildValidator(\Cake\Event\EventInterface $event, Validator $validator, $name)
    {
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     * @param $operation
     */
    public function afterRules(\Cake\Event\EventInterface $event, EntityInterface $entity, \ArrayObject $options, $operation)
    {
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function beforeSave(\Cake\Event\EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
        if ($entity->isNew() && !$entity->uuid) {
            $entity->uuid = Text::uuid();
        }
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function afterSave(\Cake\Event\EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
    }

    /**
     * Find order
     *
     * @param Query $query
     * @param array $options Query conditions
     * @return mixed
     */
    public function findOrder(Query $query, array $options = [])
    {
        $query
            ->applyOptions(['status' => true])
            ->where($options)
            ->contain(['ShopCustomers' => ['Users'], 'ShopOrderItems', 'BillingAddresses' => ['Countries'], 'ShippingAddresses' => ['Countries']]);

        return $query->first();
    }

    /**
     * Find cart order
     *
     * @param Query $query
     * @param array $options Query conditions
     * @return mixed
     */
    public function findCart(Query $query, array $options = [])
    {
        return $this->findOrder($query, $options);
    }

    /**
     *
     * Save order address for order
     *
     * @param ShopOrder $order
     * @param ShopOrderAddress $address
     * @param $addressType
     * @return bool|EntityInterface|ShopOrderAddress
     */
    public function setOrderAddress(ShopOrder $order, ShopOrderAddress $address, $addressType)
    {
        $orderAddress = $this->getOrderAddress($order, $addressType);
        if (!$orderAddress) {
            $orderAddress = $this->ShopOrderAddresses->newEntity();
        }

        $orderAddress = $this->ShopOrderAddresses->patchEntity($orderAddress, $address->toArray());
        $orderAddress->shop_order_id = $order->id;
        $orderAddress->type = $addressType;

        $orderAddress = $this->ShopOrderAddresses->save($orderAddress);

        return $orderAddress;
    }

    /**
     * @param $addressType
     * @return ShopOrderAddress
     */
    public function getOrderAddress(ShopOrder $order, $addressType)
    {
        return $this->ShopOrderAddresses
            ->find()
            ->contain(['Countries'])
            ->where(['shop_order_id' => $order->id, 'type' => $addressType])
            ->first();
    }

    /**
     * Set order address from existing customer address entity or id
     *
     * @param ShopOrder $order
     * @param $address
     * @param $addressType
     * @return bool|EntityInterface|ShopOrderAddress
     * @throws \Exception
     */
    public function setOrderAddressFromCustomerAddress(ShopOrder $order, $address, $addressType)
    {
        if (is_numeric($address)) {
            $addressId = $address;
            $address = $this->ShopOrderAddresses->ShopCustomerAddresses
                ->find()
                ->where([
                    'ShopCustomerAddresses.id' => $addressId,
                    'ShopCustomerAddresses.shop_customer_id' => $order->shop_customer_id,
                ])
                ->first();

            if (!$address) {
                throw new \Exception('ShopOrdersTable::setOrderAddressFromCustomerAddress: Address not found with id ' . $addressId . ' for customer ' . $order->shop_customer_id);
            }
        }

        $addressId = $address->id;
        $address->id = null; // @TODO Use $address->extract() instead, to omit fields like 'id'
        $orderAddress = $this->ShopOrderAddresses->newEntity($address->toArray());
        $orderAddress->shop_customer_address_id = $addressId;

        return $this->setOrderAddress($order, $orderAddress, $addressType);
    }

    /**
     * Get next order nr within ordergroup
     *
     * @param null $orderGroup Defaults to config value 'Shop.Order.nrGroup'
     * @return int
     */
    public function getNextOrderNr($orderGroup = null)
    {
        $orderNr = $orderNrStart = (Shop::config('Shop.Order.nrStart')) ?: 1;
        $orderGroup = ($orderGroup) ?: Shop::config('Shop.Order.nrGroup');

        $lastOrder = $this->find()
            ->select(['id', 'nr', 'ordergroup'])
            ->contain([])
            ->where(['is_temporary' => false, 'nr IS NOT NULL', 'ordergroup' => (string)$orderGroup])
            ->order(['nr' => 'DESC'])
            ->first();

        if ($lastOrder && $lastOrder->nr) {
            $orderNr = (int)$lastOrder->nr + 1;
        }

        return $orderNr;
    }

    /**
     * Get next order nr within ordergroup
     *
     * @param null $orderGroup Defaults to config value 'Shop.Order.nrGroup'
     * @return int
     */
    public function getNextInvoiceNr($orderGroup = null)
    {
        $nextNr = $orderNrStart = (Shop::config('Shop.Invoice.nrStart')) ?: 1;
        $orderGroup = ($orderGroup) ?: Shop::config('Shop.Invoice.nrGroup');

        $lastOrder = $this->find()
            ->select(['id', 'invoice_nr', 'ordergroup'])
            ->contain([])
            ->where(['is_temporary' => false, 'invoice_nr IS NOT NULL', 'ordergroup' => (string)$orderGroup])
            ->order(['nr' => 'DESC'])
            ->first();

        if ($lastOrder && $lastOrder->invoice_nr) {
            $nextNr = (int)$lastOrder->invoice_nr + 1;
        }

        return $nextNr;
    }

    /**
     * @param $id
     * @param bool|true $update
     * @return bool|EntityInterface|mixed|ShopOrder
     */
    public function calculate($id, $update = true)
    {
        $order = $this->get($id, ['contain' => ['ShopOrderItems']]);

        $order = $this->calculateOrder($order);
        if ($update) {
            $order = $this->save($order);
        }

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @return ShopOrder
     */
    public function calculateOrder(ShopOrder $order)
    {
        $calculator = $this->_calculateOrderCosts($order);
        $itemsValue = $calculator->getValue('order_items');

        $order->items_value_net = $itemsValue->getNetValue();
        $order->items_value_tax = $itemsValue->getTaxValue();
        $order->items_value_taxed = $itemsValue->getTotalValue();

        $order->order_value_tax = $calculator->getTaxValue();
        $order->order_value_total = $calculator->getTotalValue();

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @return CostCalculator
     */
    protected function _calculateOrderCosts(ShopOrder $order)
    {
        $calculator = new CostCalculator();

        $calculator->addValue('order_items', $this->_calculateOrderItemsCosts($order), null, null);

        // items value
        /*
        array_walk($order->shop_order_items, function ($item) use (&$calculator) {
            $calculator->addValue(
                'order_item:' . $item->id,
                $item->value_net,
                $item->tax_rate,
                sprintf("%sx %s", $item->amount, $item->title)
            );
        });
        */

        // coupon
        //$calculator->addValue('order_coupon', -100, 0, "Coupon");

        // shipping
        //$calculator->addValue('shipping', 32, 10, "Shipping costs");

        return $calculator;
    }

    /**
     * @param ShopOrder $order
     * @return CostCalculator
     */
    protected function _calculateOrderItemsCosts(ShopOrder $order)
    {
        $calculator = new CostCalculator();

        $reverseCharge = $order->isReverseCharge();

        // items value
        $items = (array)$order->shop_order_items;
        array_walk($items, function ($item) use (&$calculator, $reverseCharge) {
            $taxRate = ($reverseCharge) ? 0 : $item->tax_rate;

            $calculator->addValue(
                'order_item:' . $item->id,
                $item->value_net,
                $taxRate,
                sprintf("%sx %s", $item->amount, $item->title)
            );
        });

        return $calculator;
    }

    /**
     * Set a new update status for order
     *
     * @param ShopOrder $order
     * @param $newStatus
     * @return bool|ShopOrder
     * @deprecated Use updateStatus() instead
     */
    public function updateOrderStatus(ShopOrder $order, $newStatus)
    {
        return $this->updateStatus($order, $newStatus);
    }

    /**
     * Set a new update status for order
     *
     * @param ShopOrder $order
     * @param $newStatus
     * @return bool|ShopOrder
     */
    public function updateStatus(ShopOrder $order, $newStatus)
    {
        $oldStatus = $order->status;
        $order->status = $newStatus;

        if ($newStatus == static::ORDER_STATUS_SUBMITTED && !$order->submitted) {
            $order->submitted = time();
        } elseif ($newStatus == static::ORDER_STATUS_CONFIRMED && !$order->confirmed) {
            $order->confirmed = time();
        } elseif ($newStatus == static::ORDER_STATUS_PAYED && !$order->payed) {
            $order->payed = time();
        } elseif ($newStatus == static::ORDER_STATUS_DELIVERED && !$order->delivered) {
            $order->delivered = time();
        } elseif ($newStatus == static::ORDER_STATUS_CLOSED && !$order->invoiced) {
            $order->invoiced = time();
        }

        if (!$this->save($order)) {
            Log::error(sprintf("Shop Order: Failed to updated order status for order %s from %s to %s", $order->id, $oldStatus, $newStatus));

            return false;
        }

        //@TODO Add order history entry
        Log::info(sprintf("Shop Order: Updated order status for order %s from %s to %s", $order->id, $oldStatus, $newStatus));

        $event = new Event('Shop.Model.Order.statusUpdate', $this, [
            'order' => $order,
        ]);
        $this->getEventManager()->dispatch($event);

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @param ShopOrderTransaction $transaction
     * @return bool|ShopOrder
     */
    public function updateStatusFromTransaction(ShopOrder $order, ShopOrderTransaction $transaction)
    {
        $newStatus = $this->_mapTransactionStatus($transaction->status);

        return $this->updateStatus($order, $newStatus);
    }

    /**
     * @param $status
     * @return int
     */
    protected function _mapTransactionStatus($status)
    {
        switch ($status) {
            case ShopOrderTransactionsTable::STATUS_INIT:
            case ShopOrderTransactionsTable::STATUS_SUSPENDED:
                return ShopOrdersTable::ORDER_STATUS_PENDING;

            case ShopOrderTransactionsTable::STATUS_RESERVED:
            case ShopOrderTransactionsTable::STATUS_CONFIRMED:
                return ShopOrdersTable::ORDER_STATUS_CONFIRMED;

            case ShopOrderTransactionsTable::STATUS_ERROR:
            case ShopOrderTransactionsTable::STATUS_REJECTED:
                return ShopOrdersTable::ORDER_STATUS_ERROR;

            case ShopOrderTransactionsTable::STATUS_REVERSAL:
            case ShopOrderTransactionsTable::STATUS_CREDITED:
                return ShopOrdersTable::ORDER_STATUS_STORNO;

            default:
                break;
        }
    }

    /**
     * Assign next available order number
     *
     * @param ShopOrder $order
     * @return bool|EntityInterface|mixed|ShopOrder
     */
    public function assignOrderNr(ShopOrder $order)
    {
        // check if an order number has already been assigned
        if ($order->nr) {
            return $order;
        }

        $config = Shop::config('Shop.Order');

        return $this->getConnection()->transactional(function ($conn) use (&$order, $config) {
            $order->nr = $this->getNextOrderNr();
            $order->ordergroup = $config['nrGroup'];

            return $this->save($order);
        });
    }

    /**
     * Assign next available order number
     *
     * @param ShopOrder $order
     * @return bool|EntityInterface|mixed|ShopOrder
     */
    public function assignInvoiceNr(ShopOrder $order)
    {
        // check if an order number has already been assigned
        if ($order->invoice_nr) {
            return $order;
        }

        $config = Shop::config('Shop.Order');

        return $this->getConnection()->transactional(function ($conn) use (&$order, $config) {
            $order->invoice_nr = $this->getNextInvoiceNr();
            $order->invoiced = Time::now();

            return $this->save($order);
        });
    }

    /**
     * @param ShopOrder $order
     * @param array $data
     * @return bool|EntityInterface|mixed|ShopOrder
     * @throws \Exception
     */
    public function submitOrder(ShopOrder $order, array $data = [])
    {
        // force reload order
        //$order = $this->get($order->id);

        if ($order->status > self::ORDER_STATUS_SUBMITTED) {
            throw new \Exception("Order already submitted");
        }

        // re-calculate order
        $order = $this->calculateOrder($order);

        // save order
        $submitData = array_merge([
            'uuid' => ($order->uuid) ?: Text::uuid(), //@TODO This can be ommited, as uuid is already injected in the 'beforeSave' callback
            'submitted' => Time::now(),
            'is_temporary' => false,
            'status' => self::ORDER_STATUS_PENDING,
            'customer_email' => ($order->customer_email) ?: $order->shop_customer->email,
        ], $data);
        $order = $this->patchEntity($order, $submitData, ['validate' => 'submit']);
        if ($order->getErrors()) {
            //debug($order->getErrors());
            Log::error("Order submitted with errors: " . $order->id);
            //throw new \Exception("Failed to submit order");
            return $order;
        }

        // dispatch 'beforeSubmit' event
        $event = new Event('Shop.Model.Order.beforeSubmit', $this, [
            'order' => $order,
        ]);
        $this->getEventManager()->dispatch($event);

        // place that order now!
        $order = $this->save($order);

        // assign order nr
        // @TODO Move to event listiner or after save method
        if (!$this->assignOrderNr($order)) {
            Log::error("Failed to assign order nr " . $order->id);
        }

        // update order status to 'submitted'
        //if (!$this->updateOrderStatus($order, self::ORDER_STATUS_SUBMITTED)) {
        //    Log::error("Shop Order: Failed to updated order status to SUBMITTED " . $order->id);
        //}

        // dispatch 'afterSubmit' event
        $event = new Event('Shop.Model.Order.afterSubmit', $this, [
            'order' => $order,
        ]);
        $this->getEventManager()->dispatch($event);

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @return ShopOrder
     */
    public function confirmOrder(ShopOrder $order)
    {
        if ($order->status >= self::ORDER_STATUS_CONFIRMED) {
            //throw new \Exception("Order already confirmed");
            return $order;
        }

        // dispatch 'beforeSubmit' event
        $event = new Event('Shop.Model.Order.beforeConfirm', $this, [
            'order' => $order,
        ]);
        $this->getEventManager()->dispatch($event);

        // update order status to 'submitted'
        // @TODO Move to event listener and check if the payment balance is actually zero before updating the status to CONFIRMED
        if (!$this->updateStatus($order, self::ORDER_STATUS_CONFIRMED)) {
            Log::error("Shop Order: Failed to updated order status to CONFIRMED " . $order->id);
        }

        // assign invoice nr
        // @TODO Move to event listener and check if the payment balance is actually zero before assigning an invoice nr
        if (!$this->assignInvoiceNr($order)) {
            Log::error("Shop Order: " . sprintf("Failed to assign invoice nr for order %s", $order->id));
        } // update order status to 'submitted'
        // @TODO Move to event listener and check if the payment balance is actually zero before updating the status to PAYED
        elseif (!$this->updateStatus($order, self::ORDER_STATUS_PAYED)) {
            Log::error("Shop Order: Failed to updated order status to PAYED " . $order->id);
        }

        // dispatch 'afterSubmit' event
        $event = new Event('Shop.Model.Order.afterConfirm', $this, [
            'order' => $order,
        ]);
        $this->getEventManager()->dispatch($event);

        return $order;
    }

    /**
     * @param ShopOrder|EntityInterface $order
     * @return bool|ShopOrder|EntityInterface
     */
    public function saveOrder(ShopOrder $order)
    {
        return $this->save($order);
    }

    /**
     * @param ShopOrder $order
     * @return bool
     */
    public function requiresShipping(ShopOrder $order)
    {
        $required = false;
        foreach ($order->shop_order_items as $orderItem) {
            if ($orderItem->requiresShipping()) {
                $required = true;
            }
        }

        return $required;
    }

    /*
    public function requiresShippingDigital(ShopOrder $order)
    {
        $required = false;
        foreach ($order->shop_order_items as $orderItem) {
            if ($orderItem->requiresShippingDigital()) {
                $required = true;
            }
        }
        return $required;
    }
    */

    /**
     * @param ShopOrder $order
     * @return bool
     */
    public function requiresShippingAddress(ShopOrder $order)
    {
        return $this->requiresShipping($order);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): \Cake\Validation\Validator
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('id', 'create');

        $validator
            ->add('uuid', 'valid', ['rule' => 'uuid'])
            ->allowEmptyString('uuid');

        $validator
            ->add('cartid', 'valid', ['rule' => 'uuid'])
            ->allowEmptyString('cartid');

        $validator
            ->allowEmptyString('sessionid');

        $validator
            ->add('nr', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('nr');

        $validator
            ->allowEmptyString('title');

        $validator
            ->add('items_value_net', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('items_value_net');

        $validator
            ->add('items_value_tax', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('items_value_tax');

        $validator
            ->add('items_value_taxed', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('items_value_taxed');

        $validator
            ->allowEmptyString('shipping_type');

        $validator
            ->add('shipping_value_net', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('shipping_value_net');

        $validator
            ->add('shipping_value_tax', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('shipping_value_tax');

        $validator
            ->add('shipping_value_taxed', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('shipping_value_taxed');

        $validator
            ->add('order_value_total', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('order_value_total');

        $validator
            ->allowEmptyString('status');

        $validator
            ->add('submitted', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('submitted');

        $validator
            ->add('confirmed', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('confirmed');

        $validator
            ->add('delivered', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('delivered');

        $validator
            ->add('invoiced', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('invoiced');

        $validator
            ->add('payed', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('payed');

        $validator
            ->allowEmptyString('customer_notes');

        $validator
            ->allowEmptyString('staff_notes');

        $validator
            ->add('shipping_use_billing', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('shipping_use_billing');

        $validator
            ->allowEmptyString('customer_phone');

        $validator
            ->allowEmptyString('customer_email');

        $validator
            ->allowEmptyString('customer_ip');

        $validator
            ->allowEmptyString('payment_type');

        $validator
            ->allowEmptyString('payment_info_1');

        $validator
            ->allowEmptyString('payment_info_2');

        $validator
            ->allowEmptyString('payment_info_3');

        $validator
            ->add('is_temporary', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('is_temporary');

        $validator
            ->add('is_storno', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('is_storno');

        $validator
            ->add('is_deleted', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('is_deleted');

        $validator
            ->add('agree_terms', 'valid', ['rule' => 'boolean'])
            ->notEmptyString('agree_terms');

        $validator
            ->add('agree_newsletter', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('agree_newsletter');

        $validator
            ->allowEmptyString('locale');

        return $validator;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationPayment(Validator $validator)
    {
        $validator
            ->notEmptyString('payment_type');

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationPaymentCreditCardInternal(Validator $validator)
    {
        $validator
            ->notEmptyString('cc_brand')
            ->requirePresence('cc_brand');

        $validator
            ->notEmptyString('cc_holder_name')
            ->requirePresence('cc_holder_name');

        $validator
            ->add('cc_number', 'valid', ['rule' => 'numeric'])
            ->notEmptyString('cc_number')
            ->requirePresence('cc_number');

        $validator
            ->notEmptyString('cc_expires_at')
            ->requirePresence('cc_expires_at');

        return $validator;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationSubmit(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $validator = $this->validationPayment($validator);
        $validator
            ->requirePresence('is_temporary')
            ->requirePresence('submitted')
            ->requirePresence('status')
            ->requirePresence('uuid')
            ->requirePresence('customer_email')
            ->notEmptyString('customer_email')
            ->requirePresence('agree_terms')
            ->notEmptyString('agree_terms')
            ->add('agree_terms', 'checked', ['rule' => function ($value) {

                return $value > 0;
            }, 'message' => __d('shop', 'Please agree to the general terms & conditions')]);

        // optional: customer phone
        if (Configure::read('Shop.Checkout.customerPhone')) {
            $validator
                ->notEmptyString('customer_phone')
                ->isPresenceRequired('customer_phone', true);
        }

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add($rules->existsIn(['shop_customer_id'], 'ShopCustomers'));

        return $rules;
    }

    /**
     * @return array
     * @deprecated
     */
    public function implementedStati()
    {
        return [
            'status' => [
                new Status(self::ORDER_STATUS_TEMP, __d('shop', 'Quote'), 'default'),
                new Status(self::ORDER_STATUS_SUBMITTED, __d('shop', 'Purchased'), 'default'),
                new Status(self::ORDER_STATUS_PENDING, __d('shop', 'Waiting for payment'), 'warning'),
                new Status(self::ORDER_STATUS_CONFIRMED, __d('shop', 'Processing payment'), 'success'),
                new Status(self::ORDER_STATUS_PAYED, __d('shop', 'Payed'), 'success'),
                new Status(self::ORDER_STATUS_DELIVERED, __d('shop', 'Delivered'), 'success'),
                new Status(self::ORDER_STATUS_CLOSED, __d('shop', 'Closed'), 'success'),
                new Status(self::ORDER_STATUS_STORNO, __d('shop', 'Storno'), 'default'),
                new Status(self::ORDER_STATUS_ERROR, __d('shop', 'Error'), 'danger'),
                new Status(self::ORDER_STATUS_ERROR_DELIVERY, __d('shop', 'Error Delivery'), 'danger'),
            ],
            'shipping_status' => [
                new Status(self::SHIPPING_STATUS_STANDBY, __d('shop', 'Not delivered'), 'danger'),
                new Status(self::SHIPPING_STATUS_PENDING, __d('shop', 'Pending'), 'warning'),
                new Status(self::SHIPPING_STATUS_DELIVERED, __d('shop', 'Delivered'), 'success'),
            ],
            'payment_status' => [
                new Status(self::PAYMENT_STATUS_PENDING, __d('shop', 'Waiting for payment'), 'warning'),
                new Status(self::PAYMENT_STATUS_PARTIAL, __d('shop', 'Teilzahlung erhalten'), 'warning'),
                new Status(self::PAYMENT_STATUS_PAYED, __d('shop', 'Payed'), 'success'),
            ],
        ];
    }
}
