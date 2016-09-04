<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrder;

/**
 * ShopOrders Model
 *
 * @property \Cake\ORM\Association\HasMany $ShopCarts
 * @property \Cake\ORM\Association\HasMany $ShopOrderItems
 */
class ShopOrdersTable extends Table
{

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

        $this->hasMany('ShopCarts', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopCarts'
        ]);
        $this->hasMany('ShopOrderItems', [
            'foreignKey' => 'shop_order_id',
            'className' => 'Shop.ShopOrderItems'
        ]);
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
            ->allowEmpty('agree_terms');

        $validator
            ->add('agree_newsletter', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('agree_newsletter');

        $validator
            ->allowEmpty('locale');

        return $validator;
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
}
