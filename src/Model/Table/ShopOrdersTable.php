<?php
namespace Shop\Model\Table;

use Banana\Lib\Status;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
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
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('shop_orders');
        $this->displayField('nr_formatted');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomers'
        ]);
        /*
        $this->hasMany('ShopCarts', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopCarts'
        ]);
        */
        $this->hasMany('ShopOrderItems', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderItems'
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
            'contain' => ['Countries']
        ]);
        $this->hasOne('ShippingAddresses', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
            'conditions' => ['ShippingAddresses.type' => 'S'],
            'contain' => ['Countries']
        ]);

        $this->addBehavior('Banana.Statusable');

        if (Plugin::loaded('Search')) {

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
                    'field' => ['title']
                ])
                ->value('shop_customer_id', [
                    'filterEmpty' => true
                ])
                ->value('status', [
                    'filterEmpty' => true
                ])
                ->value('payment_status', [
                    'filterEmpty' => true
                ])
                ->value('shipping_status', [
                    'filterEmpty' => true
                ])
                ->add('nr_formatted', 'Search.Callback', [
                    'callback' => function ($query, $args, $filter) {
                         return $query;
                    }
                ]);
        }
    }

    /**
     * @param Event $event
     * @param Validator $validator
     * @param $name
     */
    public function buildValidator(Event $event, Validator $validator, $name)
    {
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     * @param $operation
     */
    public function afterRules(Event $event, EntityInterface $entity, \ArrayObject $options, $operation)
    {
    }

    /**
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options)
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
    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options)
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
                    'ShopCustomerAddresses.shop_customer_id' => $order->shop_customer_id
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
            ->where(['is_temporary' => false, 'nr IS NOT NULL', 'ordergroup' => (string) $orderGroup])
            ->order(['nr' => 'DESC'])
            ->first();

        if ($lastOrder && $lastOrder->nr) {
            $orderNr = (int) $lastOrder->nr + 1;
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
            ->where(['is_temporary' => false, 'invoice_nr IS NOT NULL', 'ordergroup' => (string) $orderGroup])
            ->order(['nr' => 'DESC'])
            ->first();

        if ($lastOrder && $lastOrder->invoice_nr) {
            $nextNr = (int) $lastOrder->invoice_nr + 1;
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
        $items = (array) $order->shop_order_items;
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
        switch ($status)
        {
            case ShopOrderTransactionsTable::STATUS_INIT:
            case ShopOrderTransactionsTable::STATUS_SUSPENDED:
                return ShopOrdersTable::ORDER_STATUS_PENDING;

            case ShopOrderTransactionsTable::STATUS_RESERVED:
            case ShopOrderTransactionsTable::STATUS_CONFIRMED:
                return ShopOrdersTable::ORDER_STATUS_PAYED;

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

        return $this->connection()->transactional(function($conn) use (&$order, $config) {
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

        return $this->connection()->transactional(function($conn) use (&$order, $config) {
            $order->invoice_nr = $this->getNextInvoiceNr();
            return $this->save($order);
        });
    }

    /**
     * @param ShopOrder $order
     * @param array $options
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
        if ($order->errors()) {
            //debug($order->errors());
            Log::error("Order submitted with errors: " . $order->id);
            //throw new \Exception("Failed to submit order");
            return $order;
        }

        // dispatch 'beforeSubmit' event
        $event = new Event('Shop.Model.Order.beforeSubmit', $this, [
            'order' => $order
        ]);
        $this->eventManager()->dispatch($event);

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
            'order' => $order
        ]);
        $this->eventManager()->dispatch($event);

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @param array $data
     * @return ShopOrder
     */
    public function confirmOrder(ShopOrder $order, array $data = [])
    {
        if ($order->status >= self::ORDER_STATUS_CONFIRMED) {
            //throw new \Exception("Order already confirmed");
            return $order;
        }

        // dispatch 'beforeSubmit' event
        $event = new Event('Shop.Model.Order.beforeConfirm', $this, [
            'order' => $order
        ]);
        $this->eventManager()->dispatch($event);

        // update order status to 'submitted'
        if (!$this->updateStatus($order, self::ORDER_STATUS_CONFIRMED)) {
            Log::error("Shop Order: Failed to updated order status to CONFIRMED " . $order->id);
        }

        // dispatch 'afterSubmit' event
        $event = new Event('Shop.Model.Order.afterConfirm', $this, [
            'order' => $order
        ]);
        $this->eventManager()->dispatch($event);

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @return bool|EntityInterface|mixed
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->add('uuid', 'valid', ['rule' => 'uuid'])
            ->allowEmpty('uuid');

        $validator
            ->add('cartid', 'valid', ['rule' => 'uuid'])
            ->allowEmpty('cartid');

        $validator
            ->allowEmpty('sessionid');

        $validator
            ->add('nr', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('nr');

        $validator
            ->allowEmpty('title');

        $validator
            ->add('items_value_net', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('items_value_net');

        $validator
            ->add('items_value_tax', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('items_value_tax');

        $validator
            ->add('items_value_taxed', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('items_value_taxed');

        $validator
            ->allowEmpty('shipping_type');

        $validator
            ->add('shipping_value_net', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('shipping_value_net');

        $validator
            ->add('shipping_value_tax', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('shipping_value_tax');

        $validator
            ->add('shipping_value_taxed', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('shipping_value_taxed');

        $validator
            ->add('order_value_total', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('order_value_total');

        $validator
            ->allowEmpty('status');

        $validator
            ->add('submitted', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('submitted');

        $validator
            ->add('confirmed', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('confirmed');

        $validator
            ->add('delivered', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('delivered');

        $validator
            ->add('invoiced', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('invoiced');

        $validator
            ->add('payed', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('payed');

        $validator
            ->allowEmpty('customer_notes');

        $validator
            ->allowEmpty('staff_notes');

        $validator
            ->add('shipping_use_billing', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('shipping_use_billing');

        $validator
            ->allowEmpty('customer_phone');

        $validator
            ->allowEmpty('customer_email');

        $validator
            ->allowEmpty('customer_ip');

        $validator
            ->allowEmpty('payment_type');

        $validator
            ->allowEmpty('payment_info_1');

        $validator
            ->allowEmpty('payment_info_2');

        $validator
            ->allowEmpty('payment_info_3');

        $validator
            ->add('is_temporary', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_temporary');

        $validator
            ->add('is_storno', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_storno');

        $validator
            ->add('is_deleted', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_deleted');

        $validator
            ->add('agree_terms', 'valid', ['rule' => 'boolean'])
            ->notEmpty('agree_terms');

        $validator
            ->add('agree_newsletter', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('agree_newsletter');

        $validator
            ->allowEmpty('locale');

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
            ->notEmpty('payment_type')
        ;

        return $validator;
    }

    /**
     * @param Validator $validator
     * @return Validator
     */
    public function validationPaymentCreditCardInternal(Validator $validator)
    {
        $validator
            ->notEmpty('cc_brand')
            ->requirePresence('cc_brand');

        $validator
            ->notEmpty('cc_holder_name')
            ->requirePresence('cc_holder_name');

        $validator
            ->add('cc_number', 'valid', ['rule' => 'numeric'])
            ->notEmpty('cc_number')
            ->requirePresence('cc_number');

        $validator
            ->notEmpty('cc_expires_at')
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
            ->notEmpty('customer_email')
            ->requirePresence('agree_terms')
            ->notEmpty('agree_terms')
            ->add('agree_terms', 'checked', ['rule' => function ($value) { return $value > 0; }, 'message' => __d('shop','Please agree to the general terms & conditions')]);

        // optional: customer phone
        if (Configure::read('Shop.Checkout.customerPhone')) {
            $validator
                ->notEmpty('customer_phone')
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
    public function buildRules(RulesChecker $rules)
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
                new Status(self::ORDER_STATUS_TEMP, __d('shop','Quote'), 'default'),
                new Status(self::ORDER_STATUS_SUBMITTED, __d('shop','Purchased'), 'default'),
                new Status(self::ORDER_STATUS_PENDING, __d('shop','Waiting for payment'), 'warning'),
                new Status(self::ORDER_STATUS_CONFIRMED, __d('shop','Processing payment'), 'success'),
                new Status(self::ORDER_STATUS_PAYED, __d('shop','Payed'), 'success'),
                new Status(self::ORDER_STATUS_DELIVERED, __d('shop','Delivered'), 'success'),
                new Status(self::ORDER_STATUS_CLOSED, __d('shop','Closed'), 'success'),
                new Status(self::ORDER_STATUS_STORNO, __d('shop','Storno'), 'default'),
                new Status(self::ORDER_STATUS_ERROR, __d('shop','Error'), 'danger'),
                new Status(self::ORDER_STATUS_ERROR_DELIVERY, __d('shop','Error Delivery'), 'danger'),
            ],
            'shipping_status' => [
                new Status(self::SHIPPING_STATUS_STANDBY, __d('shop','Not delivered'), 'danger'),
                new Status(self::SHIPPING_STATUS_PENDING, __d('shop','Pending'), 'warning'),
                new Status(self::SHIPPING_STATUS_DELIVERED, __d('shop','Delivered'), 'success'),
            ],
            'payment_status' => [
                new Status(self::PAYMENT_STATUS_PENDING, __d('shop','Waiting for payment'), 'warning'),
                new Status(self::PAYMENT_STATUS_PARTIAL, __d('shop','Teilzahlung erhalten'), 'warning'),
                new Status(self::PAYMENT_STATUS_PAYED, __d('shop','Payed'), 'success')
            ],
        ];
    }
}
