<?php
namespace Shop\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopOrderItem;

/**
 * ShopOrderItems Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ShopOrders
 */
class ShopOrderItemsTable extends Table
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

        $this->table('shop_order_items');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders'
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
            ->allowEmpty('refscope');

        $validator
            ->add('refid', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('refid');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->add('amount', 'valid', ['rule' => 'numeric'])
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->requirePresence('unit', 'create')
            ->notEmpty('unit');

        $validator
            ->add('item_value_net', 'valid', ['rule' => 'numeric'])
            ->requirePresence('item_value_net', 'create')
            ->notEmpty('item_value_net');

        $validator
            ->add('tax_rate', 'valid', ['rule' => 'numeric'])
            ->requirePresence('tax_rate', 'create')
            ->notEmpty('tax_rate');

        $validator
            ->add('value_net', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value_net', 'create')
            ->notEmpty('value_net');

        $validator
            ->add('value_tax', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value_tax', 'create')
            ->notEmpty('value_tax');

        $validator
            ->add('value_total', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value_total', 'create')
            ->notEmpty('value_total');

        $validator
            ->allowEmpty('options');

        return $validator;
    }

    public function beforeRules(Event $event, EntityInterface $entity, \ArrayObject $options, $operation)
    {
        $entity->calculate();
    }

    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        if ($entity->shop_order_id) {
            $this->ShopOrders->calculate($entity->shop_order_id);
        }
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
        $rules->add($rules->existsIn(['shop_order_id'], 'ShopOrders'));
        return $rules;
    }
}
