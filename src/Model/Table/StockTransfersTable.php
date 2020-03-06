<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\StockTransfer;

/**
 * StockTransfers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentStockTransfers
 * @property \Cake\ORM\Association\BelongsTo $ShopStocks
 * @property \Cake\ORM\Association\BelongsTo $ShopProducts
 * @property \Cake\ORM\Association\HasMany $ChildStockTransfers
 */
class StockTransfersTable extends Table
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

        $this->setTable('shop_stock_transfers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ParentStockTransfers', [
            'className' => 'Shop.StockTransfers',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('ShopStocks', [
            'foreignKey' => 'shop_stock_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopStocks'
        ]);
        $this->belongsTo('ShopProducts', [
            'foreignKey' => 'shop_product_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopProducts'
        ]);
        $this->hasMany('ChildStockTransfers', [
            'className' => 'Shop.StockTransfers',
            'foreignKey' => 'parent_id'
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
            ->add('op', 'valid', ['rule' => 'numeric'])
            ->requirePresence('op', 'create')
            ->notEmpty('op');

        $validator
            ->add('amount', 'valid', ['rule' => 'numeric'])
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->add('date', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('date');

        $validator
            ->allowEmpty('comment');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentStockTransfers'));
        $rules->add($rules->existsIn(['shop_stock_id'], 'ShopStocks'));
        $rules->add($rules->existsIn(['shop_product_id'], 'ShopProducts'));

        return $rules;
    }
}
