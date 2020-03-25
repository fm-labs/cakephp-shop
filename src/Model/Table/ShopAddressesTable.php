<?php
declare(strict_types=1);

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
    public const TYPE_BILLING = 'B';
    public const TYPE_SHIPPING = 'S';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('shop_customer_addresses');
        $this->setDisplayField('oneline');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopCustomers', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomers',
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
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->requirePresence('street', 'create')
            ->notEmptyString('street');

        $validator
            //->requirePresence('street2', 'create')
            ->allowEmptyString('street2');

        $validator
            ->requirePresence('zipcode', 'create')
            ->notEmptyString('zipcode');

        $validator
            ->requirePresence('city', 'create')
            ->notEmptyString('city');

        $validator
            ->allowEmptyString('country'); //@TODO Drop deprecated 'country' field

        $validator
            ->allowEmptyString('country_iso2'); //@TODO VAlidate ISO2 country format

        $validator
            ->add('country_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('country_id', 'create')
            ->notEmptyString('country_id');

        $validator
            ->add('is_archived', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('is_archived');

        // optional company name
        if (Shop::config('Shop.Address.useCompanyName')) {
            $validator->requirePresence('company_name', 'create');
        }
        $validator
            ->allowEmptyString('company_name');

        // optional taxid
        if (Shop::config('Shop.Address.useTaxId')) {
            $validator->requirePresence('taxid', 'create');
        }
        $validator
            ->allowEmptyString('taxid')
            ->add('taxid', 'eu_vat_number', ['rule' => function ($value, $context) {
                return EuVatNumber::validate($value);
            }]);

        return $validator;
    }

    public function buildRules(RulesChecker $rules): \Cake\ORM\RulesChecker
    {
        $rules->add(function ($entity, $options) {
            if (!$entity->taxid) {
                return true;
            }
            $validator = new EuVatValidator();

            return $validator->checkVat($entity->taxid);
        }, 'eu_vat_validation', [
            'errorField' => 'taxid',
            'message' => __d('shop', 'Please provide a valid European VAT ID'),
        ]);

        return $rules;
    }
}
