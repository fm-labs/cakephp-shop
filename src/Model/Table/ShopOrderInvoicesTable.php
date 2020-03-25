<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('shop_order_invoices');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentShopOrderInvoices', [
            'className' => 'Shop.ShopOrderInvoices',
            'foreignKey' => 'parent_id',
        ]);
        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders',
        ]);
        $this->hasMany('ChildShopOrderInvoices', [
            'className' => 'Shop.ShopOrderInvoices',
            'foreignKey' => 'parent_id',
        ]);
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
            ->requirePresence('group', 'create')
            ->notEmptyString('group');

        $validator
            ->add('nr', 'valid', ['rule' => 'numeric'])
            ->requirePresence('nr', 'create')
            ->notEmptyString('nr');

        $validator
            ->add('date_invoice', 'valid', ['rule' => 'date'])
            ->requirePresence('date_invoice', 'create')
            ->notEmptyString('date_invoice');

        $validator
            ->allowEmptyString('title');

        $validator
            ->add('value_total', 'valid', ['rule' => 'decimal'])
            ->requirePresence('value_total', 'create')
            ->notEmptyString('value_total');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->add('customer_notify_sent', 'valid', ['rule' => 'boolean'])
            ->requirePresence('customer_notify_sent', 'create')
            ->notEmptyString('customer_notify_sent');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentShopOrderInvoices'));
        $rules->add($rules->existsIn(['shop_order_id'], 'ShopOrders'));

        return $rules;
    }
}
