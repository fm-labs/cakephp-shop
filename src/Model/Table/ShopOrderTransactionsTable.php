<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrderTransaction;

/**
 * ShopOrderTransactions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopOrders
 */
class ShopOrderTransactionsTable extends Table
{



    const STATUS_INIT = 0;
    const STATUS_ERROR = 1;
    const STATUS_SUSPENDED = 2;
    const STATUS_REJECTED = 3;
    const STATUS_RESERVED = 4;
    const STATUS_CONFIRMED = 5;
    const STATUS_REVERSAL = 6;
    const STATUS_CREDITED = 7;


    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('shop_order_transactions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders'
        ]);

        $this->hasMany('ShopOrderTransactionNotifies', [
            'foreignKey' => 'shop_order_transaction_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrderTransactionNotifies'
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
            ->requirePresence('currency_code', 'create')
            ->notEmpty('currency_code');

        $validator
            ->add('value', 'valid', ['rule' => 'decimal'])
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->allowEmpty('ext_txnid');

        $validator
            ->allowEmpty('ext_status');

        $validator
            ->allowEmpty('redirect_url');

        $validator
            ->allowEmpty('custom1');

        $validator
            ->allowEmpty('custom2');

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
        $rules->add($rules->existsIn(['shop_order_id'], 'ShopOrders'));
        return $rules;
    }

}
