<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\StockValue;

/**
 * StockValues Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopStocks
 * @property \Cake\ORM\Association\BelongsTo $ShopProducts
 */
class StockValuesTable extends Table
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

        $this->table('shop_stock_values');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

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
            ->add('value', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->add('last_transfer_in', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('last_transfer_in');

        $validator
            ->add('last_transfer_out', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('last_transfer_out');

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
        $rules->add($rules->existsIn(['shop_product_id'], 'ShopProducts'));
        $rules->add($rules->existsIn(['shop_stock_id'], 'ShopStocks'));
        return $rules;
    }
}
