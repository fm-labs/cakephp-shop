<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopCoupons Model
 *
 * @method \Shop\Model\Entity\ShopCoupon newEmptyEntity()
 * @method \Shop\Model\Entity\ShopCoupon newEntity(array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCoupon[] newEntities(array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCoupon get($primaryKey, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCoupon[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopCoupon|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Shop\Model\Entity\ShopCoupon[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShopCouponsTable extends Table
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

        $this->setTable('shop_coupons');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('code')
            ->maxLength('code', 45)
            ->notEmptyString('code')
            ->add('code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('type')
            ->maxLength('type', 45)
            ->notEmptyString('type');

        $validator
            ->decimal('value')
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        $validator
            ->scalar('valuetype')
            ->maxLength('valuetype', 45)
            ->notEmptyString('valuetype');

        $validator
            ->nonNegativeInteger('max_use')
            ->notEmptyString('max_use');

        $validator
            ->nonNegativeInteger('max_use_per_customer')
            ->notEmptyString('max_use_per_customer');

        $validator
            ->boolean('is_published')
            ->notEmptyString('is_published');

        $validator
            ->dateTime('valid_from')
            ->allowEmptyDateTime('valid_from');

        $validator
            ->dateTime('valid_until')
            ->allowEmptyDateTime('valid_until');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['code']), ['errorField' => 'code']);

        return $rules;
    }
}
