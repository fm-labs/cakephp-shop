<?php
namespace Shop\Model\Table;

use Cake\Log\Log;
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
class ShopCustomerAddressesTable extends ShopAddressesTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('shop_customer_addresses');
        $this->setPrimaryKey('id');
        $this->entityClass('Shop.ShopAddress');
        $this->setDisplayField('name');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomers',
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
        $validator = parent::validationDefault($validator);

        $validator
            ->add('shop_customer_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('shop_customer_id')
            ->notEmpty('shop_customer_id');

        return $validator;
    }

    public function newRecordFromOrderAddress($customerId, ShopOrderAddress $address)
    {
        $data = $address->extractAddress();
        $data['shop_customer_id'] = $customerId;

        $customerAddress = $this->find()->where($data)->first();
        if ($customerAddress) {
            Log::debug("ShopCustomerAddresses::newRecordFromOrderAddress: Address already exists with id " . $customerAddress->id);

            return $customerAddress;
        }

        $customerAddress = $this->newEntity($data);
        if ($customerAddress->getErrors()) {
            Log::error("ShopCustomerAddresses::newRecordFromOrderAddress: Address invalid: " . json_encode($customerAddress->getErrors()));
        }

        return $this->save($customerAddress, ['checkRules' => false]);
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

        $rules->add($rules->existsIn(['shop_customer_id'], 'ShopCustomers'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));

        return $rules;
    }
}
