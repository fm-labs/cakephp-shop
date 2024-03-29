<?php
declare(strict_types=1);

namespace Shop\Model\Table;

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
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('shop_countries');
        $this->setDisplayField('name_de');
        $this->setPrimaryKey('id');

        $this->addBehavior('Cupcake.Publish');
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
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('iso2', 'create')
            ->notEmptyString('iso2')
            ->add('iso2', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('iso3', 'create')
            ->notEmptyString('iso3')
            ->add('iso3', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->requirePresence('name_de', 'create')
            ->notEmptyString('name_de');

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->notEmptyString('priority');

        $validator
            ->boolean('is_published')
            ->requirePresence('is_published', 'create')
            ->notEmptyString('is_published');

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
        $rules->add($rules->isUnique(['iso2']));
        $rules->add($rules->isUnique(['iso3']));

        return $rules;
    }
}
