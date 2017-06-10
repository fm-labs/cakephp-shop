<?php
namespace Shop\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Lib\EuVatNumber;
use Shop\Lib\EuVatValidator;
use Shop\Lib\Shop;

/**
 * ShopAddresses Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopCustomers
 */
abstract class ShopAddressesTable extends Table
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
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->requirePresence('street', 'create')
            ->notEmpty('street');

        $validator
            //->requirePresence('street2', 'create')
            ->allowEmpty('street2');

        $validator
            ->requirePresence('zipcode', 'create')
            ->notEmpty('zipcode');

        $validator
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->allowEmpty('country'); //@TODO Drop deprecated 'country' field

        $validator
            ->allowEmpty('country_iso2'); //@TODO VAlidate ISO2 country format

        $validator
            ->add('country_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('country_id', 'create')
            ->notEmpty('country_id');

        $validator
            ->add('is_archived', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_archived');

        // optional company name
        if (Shop::config('Shop.Address.useCompanyName')) {
            $validator->requirePresence('company_name', 'create');
        }
        $validator
            ->allowEmpty('company_name');

        // optional taxid
        if (Shop::config('Shop.Address.useTaxId')) {
            $validator->requirePresence('taxid', 'create');
        }
        $validator
            ->allowEmpty('taxid')
            ->add('taxid', 'eu_vat_number', ['rule' => function ($value, $context) {
                return (EuVatNumber::validate($value));
            }]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add(function ($entity, $options) {
            if (!$entity->taxid) {
                return true;
            }
            $validator = new EuVatValidator();

            return $validator->checkVat($entity->taxid);
        }, 'eu_vat_validation', [
            'errorField' => 'taxid',
            'message' => __d('shop', 'Please provide a valid European VAT ID')
        ]);

        return $rules;
    }
}
