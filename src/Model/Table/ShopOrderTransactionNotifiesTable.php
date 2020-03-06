<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrderTransactionNotify;

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
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('shop_order_transaction_notifies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrderTransactions', [
            'foreignKey' => 'shop_order_transaction_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrderTransactions'
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
            ->requirePresence('engine', 'create')
            ->notEmpty('engine');

        $validator
            ->allowEmpty('request_ip');

        $validator
            ->allowEmpty('request_url');

        $validator
            ->allowEmpty('request_json');

        $validator
            ->add('is_valid', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_valid', 'create')
            ->notEmpty('is_valid');

        $validator
            ->add('is_processed', 'valid', ['rule' => 'boolean'])
            ->requirePresence('is_processed', 'create')
            ->notEmpty('is_processed');

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
        $rules->add($rules->existsIn(['shop_order_transaction_id'], 'ShopOrderTransactions'));

        return $rules;
    }
}
