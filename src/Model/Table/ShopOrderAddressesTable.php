<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrderAddress;

/**
 * ShopOrderAddresses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopOrders
 * @property \Cake\ORM\Association\BelongsTo $ShopCustomerAddresses
 * @property \Cake\ORM\Association\BelongsTo $Countries
 */
class ShopOrderAddressesTable extends ShopAddressesTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('shop_order_addresses');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders'
        ]);
        $this->belongsTo('ShopCustomerAddresses', [
            'foreignKey' => 'shop_customer_address_id',
            'className' => 'Shop.ShopCustomerAddresses'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id',
            'className' => 'Shop.ShopCountries',
            'propertyName' => 'relcountry'
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
        return parent::validationDefault($validator);
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
        $rules = parent::buildRules($rules);

        $rules->add($rules->existsIn(['shop_order_id'], 'ShopOrders'));
        $rules->add($rules->existsIn(['shop_customer_address_id'], 'ShopCustomerAddresses'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        return $rules;
    }
}
