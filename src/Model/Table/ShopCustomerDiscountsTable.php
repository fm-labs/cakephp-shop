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
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('shop_customer_discounts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopCustomers',
        ]);
        $this->belongsTo('ShopProducts', [
            'foreignKey' => 'shop_product_id',
            //'joinType' => 'INNER',
            'className' => 'Shop.ShopProducts',
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
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->requirePresence('valuetype', 'create')
            ->notEmptyString('valuetype');

        $validator
            ->add('value', 'valid', ['rule' => 'decimal'])
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->add('is_published', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_published', 'create')
            ->notEmptyString('is_published');

        $validator
            ->add('publish_start', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('publish_start');

        $validator
            ->add('publish_end', 'valid', ['rule' => 'datetime'])
            ->allowEmptyString('publish_end');

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
        $rules->add($rules->existsIn(['shop_product_id'], 'ShopProducts'));

        return $rules;
    }

    public function listTypes()
    {
        return [
            'permanent' => __d('shop', 'Permanent'),
            //'single' => __d('shop', 'Single Usage')
        ];
    }

    public function listValueTypes()
    {
        return [
            'value' => __d('shop', 'Fixwert'),
            'percent' => __d('shop', 'Prozent'),
        ];
    }
}
