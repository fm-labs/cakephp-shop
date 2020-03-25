<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

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
    public function initialize(array $config): void
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
    public function validationDefault(Validator $validator): \Cake\Validation\Validator
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->add('is_default', 'valid', ['rule' => 'boolean'])
            ->allowEmptyString('is_default');

        return $validator;
    }
}
