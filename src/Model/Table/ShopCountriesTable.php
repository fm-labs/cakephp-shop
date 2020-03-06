<?php
namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopCountries Model
 *
 * @method \Shop\Model\Entity\ShopCountry get($primaryKey, $options = [])
 * @method \Shop\Model\Entity\ShopCountry newEntity($data = null, array $options = [])
 * @method \Shop\Model\Entity\ShopCountry[] newEntities(array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCountry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Shop\Model\Entity\ShopCountry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCountry[] patchEntities($entities, array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCountry findOrCreate($search, callable $callback = null, $options = [])
 */
class ShopCountriesTable extends Table
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

        $this->setTable('shop_countries');
        $this->setDisplayField('name_de');
        $this->setPrimaryKey('id');

        $this->addBehavior('Banana.Publishable');
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('iso2', 'create')
            ->notEmpty('iso2')
            ->add('iso2', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('iso3', 'create')
            ->notEmpty('iso3')
            ->add('iso3', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('name_de', 'create')
            ->notEmpty('name_de');

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->notEmpty('priority');

        $validator
            ->boolean('is_published')
            ->requirePresence('is_published', 'create')
            ->notEmpty('is_published');

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
        $rules->add($rules->isUnique(['iso2']));
        $rules->add($rules->isUnique(['iso3']));

        return $rules;
    }
}
