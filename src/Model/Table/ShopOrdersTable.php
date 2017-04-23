<?php
namespace Shop\Model\Table;

use Banana\Lib\Status;
use Cake\Core\Plugin;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrder;


/**
 * ShopOrders Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopCustomers
 * @property \Cake\ORM\Association\BelongsTo $BillingAddresses
 * @property \Cake\ORM\Association\BelongsTo $ShippingAddresses
 * @property \Cake\ORM\Association\HasMany $ShopCarts
 * @property \Cake\ORM\Association\HasMany $ShopOrderItems
 */
class ShopOrdersTable extends Table
{

    /*
    const ORDER_STATUS_QUOTE = 0;
    const ORDER_STATUS_SUBMITTED = 1;
    const ORDER_STATUS_CONFIRMED = 2;
    const ORDER_STATUS_INVOICED = 3;
    const ORDER_STATUS_COMPLETE = 10;
    */


    const ORDER_STATUS_TEMP = 0;
    const ORDER_STATUS_SUBMITTED = 1;
    const ORDER_STATUS_PENDING = 2;
    const ORDER_STATUS_CONFIRMED = 3;
    const ORDER_STATUS_PAYED = 4;
    const ORDER_STATUS_DELIVERED = 5;
    const ORDER_STATUS_CLOSED = 6;
    const ORDER_STATUS_STORNO = 80;
    const ORDER_STATUS_ERROR = 90;
    const ORDER_STATUS_ERROR_DELIVERY = 91;



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
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomers'
        ]);
        /*
        $this->belongsTo('BillingAddresses', [
            'foreignKey' => 'billing_address_id',
            'className' => 'Shop.ShopAddresses'
        ]);
        $this->belongsTo('ShippingAddresses', [
            'foreignKey' => 'shipping_address_id',
            'className' => 'Shop.ShopAddresses'
        ]);
        */
        $this->hasMany('ShopCarts', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopCarts'
        ]);
        $this->hasMany('ShopOrderItems', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderItems'
        ]);
        $this->hasMany('OrderAddresses', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
        ]);

        $this->hasOne('BillingAddress', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
            'propertyName' => 'billing_address',
            'conditions' => ['BillingAddress.type' => 'B']
        ]);
        $this->hasOne('ShippingAddress', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderAddresses',
            'propertyName' => 'shipping_address',
            'conditions' => ['ShippingAddress.type' => 'S']
        ]);

        //$this->addBehavior('Banana.Statusable');


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

    public function buildValidator(Event $event, Validator $validator, $name)
    {
    }

    public function afterRules(Event $event, EntityInterface $entity, \ArrayObject $options, $operation)
    {
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {

        if ($entity->isNew() && !$entity->uuid) {
            $entity->uuid = Text::uuid();
        }
        // before 'submit'
        /*
        if ($entity->dirty('status') && $entity->status == 1) {
            if (!$entity->is_billing_selected) {
                $entity->errors('is_billing_selected', ['notempty' => __d('shop','Billing not selected')]);
                return false;
            }
        }
        */
    }

    public function afterSave(Event $event)
    {
        //@TODO save billing address in address book
        //@TODO save shipping address in address book
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
            ->allowEmpty('billing_first_name');

        $validator
            ->allowEmpty('billing_last_name');

        $validator
            ->allowEmpty('billing_name');

        $validator
            ->add('billing_is_company', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('billing_is_company');

        $validator
            ->allowEmpty('billing_address');

        $validator
            ->allowEmpty('billing_taxid');

        $validator
            ->allowEmpty('billing_zipcode');

        $validator
            ->allowEmpty('billing_city');

        $validator
            ->allowEmpty('billing_country');

        $validator
            ->add('shipping_use_billing', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('shipping_use_billing');

        $validator
            ->allowEmpty('shipping_first_name');

        $validator
            ->allowEmpty('shipping_last_name');

        $validator
            ->allowEmpty('shipping_name');

        $validator
            ->add('shipping_is_company', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('shipping_is_company');

        $validator
            ->allowEmpty('shipping_address');

        $validator
            ->allowEmpty('shipping_zipcode');

        $validator
            ->allowEmpty('shipping_city');

        $validator
            ->allowEmpty('shipping_country');

        $validator
            ->notEmpty('customer_phone');

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
    public function validationBilling(Validator $validator)
    {
        $validator
            ->notEmpty('billing_first_name')
            ->notEmpty('billing_last_name')
            //->notEmpty('billing_name')
            ->notEmpty('billing_street')
            //->notEmpty('billing_taxid')
            ->notEmpty('billing_zipcode')
            ->notEmpty('billing_city')
            ->notEmpty('billing_country')
        ;

        return $validator;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationShipping(Validator $validator)
    {
        $validator
            ->notEmpty('shipping_first_name')
            ->notEmpty('shipping_last_name')
            //->notEmpty('shipping_name')
            ->notEmpty('shipping_street')
            //->notEmpty('shipping_taxid')
            ->notEmpty('shipping_zipcode')
            ->notEmpty('shipping_city')
            ->notEmpty('shipping_country')
        ;

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
        $validator = $this->validationBilling($validator);
        $validator = $this->validationShipping($validator);
        $validator = $this->validationPayment($validator);

        $validator
            ->isPresenceRequired('submitted', true);

        $validator
            //->add('agree_terms', 'valid', ['rule' => 'boolean'])
            //->add('agree_terms', 'checked', ['rule' => ['equalTo', true], 'message' => __d('shop','This field is required')])
            //->notEmpty('agree_terms')
            ->add('agree_terms', 'myRule', [
                'rule' => function ($data, $provider) {
                    if ($data == 1) {
                        return true;
                    }
                    return __d('shop','This option is mandatory');
                }
            ])
            ->isPresenceRequired('agree_terms', true);

        $validator
            ->requirePresence('customer_email')
            ->notEmpty('customer_email');

        $validator
            ->notEmpty('customer_phone')
            ->isPresenceRequired('customer_phone', true);


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

    public function getNextOrderNr()
    {
        //@TODO Read from config
        //@TODO Support filtering from multiple fields
        $orderNr = $orderNrStart = 1;

        $lastOrder = $this->find()
            ->contain([])
            ->select(['id', 'nr'])
            ->where(['ShopOrders.is_temporary' => false, 'ShopOrders.nr IS NOT NULL'])
            ->order(['ShopOrders.nr' => 'DESC', 'ShopOrders.submitted' => 'DESC'])
            ->first();

        if ($lastOrder && $lastOrder->nr) {
            $orderNr = (int) $lastOrder->nr + 1;
        }

        return $orderNr;
    }


    public function calculate($id, $update = true)
    {
        $order = $this->get($id, ['contain' => []]);
        $orderItems = $this->ShopOrderItems->find()->where(['shop_order_id' => $id])->all()->toArray();

        // items value
        $itemsNet = $itemsTax = $itemsTaxed = 0;
        array_walk($orderItems, function ($item) use (&$itemsNet, &$itemsTax, &$itemsTaxed) {
            $itemsNet += $item->value_net;
            $itemsTax += $item->value_tax;
            $itemsTaxed += $item->value_total;
        });

        $order->items_value_net = $itemsNet;
        $order->items_value_tax = $itemsTax;
        $order->items_value_taxed = $itemsTaxed;

        $order->order_value_tax = $itemsTax;
        $order->order_value_total = $itemsTaxed;

        if ($update) {
            return $this->save($order);
        }
    }

    /**
     * @param $order
     * @return bool|EntityInterface|mixed
     * @throws \Exception
     */
    public function submit($order)
    {

        if ($order->status > 0) {
            //@TODO Prevent re-submission of already submitted orders
            debug("Warning: Order already submitted");
            //throw new \Exception("Order already submitted");
        }

        $order['uuid'] = ($order['uuid']) ?: Text::uuid(); //@TODO This can be ommited, as uuid is already injected in the 'beforeSave' callback
        $order['submitted'] = Time::now();
        $order['is_temporary'] = false;
        //$order['status'] = 1;

        $order = $this->save($order);

        // assign order nr
        if (!$this->assignOrderNr($order, true)) {
            throw new \Exception("Failed to assign order nr");
        }

        // update order status to 'submitted'
        if (!$this->updateOrderStatus($order, self::ORDER_STATUS_SUBMITTED)) {
            Log::error("Shop Order: Failed to updated order status to SUBMITTED");
        }

        if ($order) {
            $event = new Event('Shop.Model.Order.afterSubmit', $this, [
                'order' => $order
            ]);
            $this->eventManager()->dispatch($event);
        }

        /*
        // @TODO move to eventlistener
        if (!$order->billing_address_id && $order->shop_customer_id) {
            $addr = $this->BillingAddresses->newEntity();
            $addr->accessible('*', true);
            $addr = $this->BillingAddresses->patchEntity($addr, self::extractAddress($order, 'billing'));
            if (!$addr->errors() && $this->BillingAddresses->save($addr)) {
                Log::info('Shop billing address added for customerID ' . $order->shop_customer_id . ' after orderID ' . $order->id);
            } else {
                Log::error('Failed to add shop billing address for customerID ' . $order->shop_customer_id . ' after orderID ' . $order->id);
            }
        }

        // @TODO move to eventlistener
        if (!$order->shipping_use_billing && !$order->shipping_address_id && $order->shop_customer_id) {
            $addr = $this->ShippingAddresses->newEntity();
            $addr->accessible('*', true);
            $addr = $this->ShippingAddresses->patchEntity($addr, self::extractAddress($order, 'shipping'));
            if (!$addr->errors() && $this->ShippingAddresses->save($addr)) {
                Log::info('Shop shipping address added for customerID ' . $order->shop_customer_id . ' after orderID ' . $order->id);
            } else {
                Log::error('Failed to add shop shipping address for customerID ' . $order->shop_customer_id . ' after orderID ' . $order->id);
            }
        }
        */

        return $order;
    }

    public function updateOrderStatus($order, $newStatus)
    {
        $order->status = $newStatus;


        if (!$this->save($order)) {
            Log::error("Shop Order: Failed to updated order status to SUBMITTED");
            return false;
        }

        return $order;
    }

    public function assignOrderNr($order, $save = false)
    {
        // check if an order number has already been assigned
        if ($order->nr) {
            return;
        }

        $order->nr = $this->getNextOrderNr();

        if ($save) {
            return $this->save($order);
        }

        return $order;
    }

    public function implementedStati()
    {
        return [
            'status' => [
                new Status(self::ORDER_STATUS_TEMP, __d('shop','Quote'), 'default'),
                new Status(self::ORDER_STATUS_SUBMITTED, __d('shop','Purchased'), 'default'),
                new Status(self::ORDER_STATUS_PENDING, __d('shop','Pending'), 'warning'),
                new Status(self::ORDER_STATUS_CONFIRMED, __d('shop','Confirmed'), 'success'),
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

    /**
     * @param $order
     * @param string $scope
     * @return array
     * @deprecated Use ShopOrderAddresses model instead
     */
    static public function extractAddress($order, $scope = 'billing')
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
        if (isset($order[$_idKey])) {
            $addr['id'] = $order[$_idKey];
        }

        if (isset($order['shop_customer_id'])) {
            $addr['shop_customer_id'] = $order['shop_customer_id'];
        }

        foreach ($fields as $field) {

            $_key = $scope . '_' . $field;
            $addr[$field] = (isset($order[$_key])) ? $order[$_key] : null;
        }
        return $addr;

    }
}
