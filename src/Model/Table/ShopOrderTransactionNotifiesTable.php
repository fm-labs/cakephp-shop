<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopOrderTransactionNotifies Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopOrderTransactions
 */
class ShopOrderTransactionNotifiesTable extends Table
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

        $this->setTable('shop_order_transaction_notifies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrderTransactions', [
            'foreignKey' => 'shop_order_transaction_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrderTransactions',
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
            ->requirePresence('engine', 'create')
            ->notEmptyString('engine');

        $validator
            ->allowEmptyString('request_ip');

        $validator
            ->allowEmptyString('request_url');

        $validator
            ->allowEmptyString('request_json');

        $validator
            ->add('is_valid', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_valid', 'create')
            ->notEmptyString('is_valid');

        $validator
            ->add('is_processed', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_processed', 'create')
            ->notEmptyString('is_processed');

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
        $rules->add($rules->existsIn(['shop_order_transaction_id'], 'ShopOrderTransactions'));

        return $rules;
    }
}
