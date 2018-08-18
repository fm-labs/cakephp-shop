<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopCustomerDiscount;

/**
 * ShopCustomerDiscounts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopCustomers
 * @property \Cake\ORM\Association\BelongsTo $ShopProducts
 */
class ShopCustomerDiscountsTable extends Table
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

        $this->table('shop_customer_discounts');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopCustomers'
        ]);
        $this->belongsTo('ShopProducts', [
            'foreignKey' => 'shop_product_id',
            //'joinType' => 'INNER',
            'className' => 'Shop.ShopProducts'
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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('valuetype', 'create')
            ->notEmpty('valuetype');

        $validator
            ->add('value', 'valid', ['rule' => 'decimal'])
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->add('is_published', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_published', 'create')
            ->notEmpty('is_published');

        $validator
            ->add('publish_start', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('publish_start');

        $validator
            ->add('publish_end', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('publish_end');

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
        $rules->add($rules->existsIn(['shop_product_id'], 'ShopProducts'));

        return $rules;
    }
}
