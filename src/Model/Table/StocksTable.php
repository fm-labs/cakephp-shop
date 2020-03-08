<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\Stock;

/**
 * Stocks Model
 *
 * @property \Cake\ORM\Association\HasMany $ShopStockTransfers
 * @property \Cake\ORM\Association\HasMany $ShopStockValues
 */
class StocksTable extends Table
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

        $this->setTable('shop_stocks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->hasMany('ShopStockTransfers', [
            'foreignKey' => 'shop_stock_id',
            'className' => 'Shop.ShopStockTransfers',
        ]);
        $this->hasMany('ShopStockValues', [
            'foreignKey' => 'shop_stock_id',
            'className' => 'Shop.ShopStockValues',
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
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->add('is_default', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_default');

        return $validator;
    }
}
