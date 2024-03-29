<?php
declare(strict_types=1);

namespace Shop\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopOrderNotifications Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopOrders
 *
 * @method \Shop\Model\Entity\ShopOrderNotification get($primaryKey, $options = [])
 * @method \Shop\Model\Entity\ShopOrderNotification newEntity($data = null, array $options = [])
 * @method \Shop\Model\Entity\ShopOrderNotification[] newEntities(array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopOrderNotification|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Shop\Model\Entity\ShopOrderNotification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopOrderNotification[] patchEntities($entities, array $data, array $options = [])
 * @method \Shop\Model\Entity\ShopOrderNotification findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShopOrderNotificationsTable extends Table
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

        $this->setTable('shop_order_notifications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders',
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
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->allowEmptyString('message');

        $validator
            ->integer('order_status')
            ->allowEmptyString('order_status');

        $validator
            ->boolean('owner_notified')
            ->allowEmptyString('owner_notified');

        $validator
            ->boolean('customer_notified')
            ->allowEmptyString('customer_notified');

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
