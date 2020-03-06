<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopTag;

/**
 * ShopTags Model
 *
 * @property \Cake\ORM\Association\HasMany $ShopProductsTags
 */
class ShopTagsTable extends Table
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

        $this->setTable('shop_tags');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('ShopProductsTags', [
            'foreignKey' => 'shop_tag_id',
            'className' => 'Shop.ShopProductsTags'
        ]);

        $this->hasMany('ShopCategoriesTags', [
            'foreignKey' => 'shop_tag_id',
            'className' => 'Shop.ShopCategoriesTags'
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
            ->allowEmpty('group');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}
