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
 * @property ShopOrdersTable $ShopOrders
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

        $this->setTable('shop_order_items');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('ShopOrders', [
            'foreignKey' => 'shop_order_id',
            'joinType' => 'INNER',
            'className' => 'Shop.ShopOrders',
        ]);

        $this->getSchema()->setColumnType('options', 'json');
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
            ->allowEmptyString('refscope');

        $validator
            ->add('refid', 'valid', ['rule' => 'numeric'])
            ->allowEmptyString('refid');

        /*
        $validator
            ->requirePresence('title', 'create')
            ->notEmptyString('title');
        */

        $validator
            ->add('amount', 'valid', ['rule' => 'numeric'])
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->requirePresence('unit', 'create')
            ->notEmptyString('unit');

        $validator
            ->add('item_value_net', 'valid', ['rule' => 'numeric'])
            ->requirePresence('item_value_net', 'create')
            ->notEmptyString('item_value_net');

        $validator
            ->add('tax_rate', 'valid', ['rule' => 'numeric'])
            ->requirePresence('tax_rate', 'create')
            ->notEmptyString('tax_rate');

        $validator
            ->add('value_net', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value_net', 'create')
            ->notEmptyString('value_net');

        $validator
            ->add('value_tax', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value_tax', 'create')
            ->notEmptyString('value_tax');

        $validator
            ->add('value_total', 'valid', ['rule' => 'numeric'])
            ->requirePresence('value_total', 'create')
            ->notEmptyString('value_total');

        $validator
            ->allowEmptyString('options');

        return $validator;
    }

    public function beforeRules(Event $event, EntityInterface $entity, \ArrayObject $options, $operation)
    {
        //debug("beforeRules");
        //$entity->calculate();
    }

    public function beforeValidate(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        //debug("beforeValidate");
        //$entity->calculate();
    }

    public function beforeSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        //debug("beforeSave");
        $entity->calculate();

        $options = [];
        foreach ($entity->getVisible() as $prop) {
            if (preg_match('/^options\_\_(.*)$/', $prop, $matches)) {
                $options[$matches[1]] = $entity->get($prop);
            }
        }
        $entity->options = $options;
    }

    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        if ($entity->shop_order_id) {
            $this->ShopOrders->calculate($entity->shop_order_id);
        }
    }

    public function afterDelete(Event $event, EntityInterface $entity, \ArrayObject $options)
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
