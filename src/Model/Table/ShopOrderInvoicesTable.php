<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrderInvoice;

/**
 * ShopOrderInvoices Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentShopOrderInvoices
 * @property \Cake\ORM\Association\BelongsTo $ShopOrders
 * @property \Cake\ORM\Association\HasMany $ChildShopOrderInvoices
 */
class ShopOrderInvoicesTable extends Table
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

        $this->setTable('shop_order_invoices');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentShopOrderInvoices', [
            'className' => 'Shop.ShopOrderInvoices',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders'
        ]);
        $this->hasMany('ChildShopOrderInvoices', [
            'className' => 'Shop.ShopOrderInvoices',
            'foreignKey' => 'parent_id'
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
            ->requirePresence('group', 'create')
            ->notEmpty('group');

        $validator
            ->add('nr', 'valid', ['rule' => 'numeric'])
            ->requirePresence('nr', 'create')
            ->notEmpty('nr');

        $validator
            ->add('date_invoice', 'valid', ['rule' => 'date'])
            ->requirePresence('date_invoice', 'create')
            ->notEmpty('date_invoice');

        $validator
            ->allowEmpty('title');

        $validator
            ->add('value_total', 'valid', ['rule' => 'decimal'])
            ->requirePresence('value_total', 'create')
            ->notEmpty('value_total');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->add('customer_notify_sent', 'valid', ['rule' => 'boolean'])
            ->requirePresence('customer_notify_sent', 'create')
            ->notEmpty('customer_notify_sent');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentShopOrderInvoices'));
        $rules->add($rules->existsIn(['shop_order_id'], 'ShopOrders'));

        return $rules;
    }
}
