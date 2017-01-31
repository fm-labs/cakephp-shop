<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopAddress;

/**
 * ShopAddresses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopCustomers
 */
class ShopAddressesTable extends Table
{

    const TYPE_BILLING = 'B';
    const TYPE_SHIPPING = 'S';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('shop_customer_addresses');
        $this->displayField('oneline');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomers'
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
            ->allowEmpty('type');

        $validator
            ->notEmpty('first_name');

        $validator
            ->notEmpty('last_name');

        $validator
            ->add('is_company', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_company');

        $validator
            ->allowEmpty('taxid');

        $validator
            ->notEmpty('street');

        $validator
            ->allowEmpty('street2');

        $validator
            ->notEmpty('zipcode');

        $validator
            ->notEmpty('city');

        $validator
            ->notEmpty('country');

        $validator
            ->allowEmpty('country_iso2');

        $validator
            ->add('is_archived', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_archived');

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
}
