<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cupcake\Lib\Status;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopOrderTransactions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopOrders
 */
class ShopOrderTransactionsTable extends Table
{
    public const STATUS_INIT = 0;
    public const STATUS_ERROR = 1;
    public const STATUS_SUSPENDED = 2;
    public const STATUS_REJECTED = 3;
    public const STATUS_RESERVED = 4;
    public const STATUS_CONFIRMED = 5;
    public const STATUS_REVERSAL = 6;
    public const STATUS_CREDITED = 7;
    public const STATUS_USER_ABORT = -1;
    public const STATUS_INTERNAL_ERROR = -2;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('shop_order_transactions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Cupcake.Statusable');

        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders',
        ]);

        $this->hasMany('ShopOrderTransactionNotifies', [
            'foreignKey' => 'shop_order_transaction_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrderTransactionNotifies',
        ]);
    }

    /**
     * @return array
     * @deprecated
     */
    public function implementedStati()
    {
        return [
            'status' => [
                new Status(self::STATUS_INIT, __d('shop', 'Initialized'), 'info'),
                new Status(self::STATUS_ERROR, __d('shop', 'Error'), 'danger'),
                new Status(self::STATUS_SUSPENDED, __d('shop', 'Suspended'), 'danger'),
                new Status(self::STATUS_REJECTED, __d('shop', 'Rejected'), 'danger'),
                new Status(self::STATUS_RESERVED, __d('shop', 'Reserved'), 'info'),
                new Status(self::STATUS_CONFIRMED, __d('shop', 'Confirmed'), 'success'),
                new Status(self::STATUS_REVERSAL, __d('shop', 'Reversal'), 'dark'),
                new Status(self::STATUS_CREDITED, __d('shop', 'Credited'), 'success'),
                new Status(self::STATUS_USER_ABORT, __d('shop', 'Aborted by user'), 'warning'),
            ],
        ];
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
            ->requirePresence('currency_code', 'create')
            ->notEmptyString('currency_code');

        $validator
            ->add('value', 'valid', ['rule' => 'decimal'])
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->allowEmptyString('ext_txnid');

        $validator
            ->allowEmptyString('ext_status');

        $validator
            ->allowEmptyString('redirect_url');

        $validator
            ->allowEmptyString('custom1');

        $validator
            ->allowEmptyString('custom2');

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
        $rules->add($rules->existsIn(['shop_order_id'], 'ShopOrders'));

        return $rules;
    }
}
