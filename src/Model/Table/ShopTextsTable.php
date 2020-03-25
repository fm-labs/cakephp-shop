<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopText;

/**
 * ShopTexts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Models
 */
class ShopTextsTable extends Table
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

        $this->setTable('shop_texts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        /*
        $this->belongsTo('Models', [
            'foreignKey' => 'model_id',
            'joinType' => 'INNER',
            'className' => 'Shop.Models'
        ]);
        */
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
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('model', 'create')
            ->notEmptyString('model');

        $validator
            ->allowEmptyString('model_scope');

        $validator
            ->allowEmptyString('locale');

        $validator
            ->allowEmptyString('format');

        $validator
            ->allowEmptyString('text');

        $validator
            ->allowEmptyString('class');

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
        //$rules->add($rules->existsIn(['model_id'], 'Models'));
        return $rules;
    }
}
