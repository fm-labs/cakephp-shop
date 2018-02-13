<?php
namespace Shop\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShopCustomers Model
 *
 * @property \Cake\ORM\Association\HasMany $ShopAddresses
 * @property \Cake\ORM\Association\HasMany $ShopOrders
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class ShopCustomersTable extends Table
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

        $this->table('shop_customers');
        $this->displayField('display_name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ShopAddresses', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopAddresses'
        ]);
        $this->hasMany('ShopOrders', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopOrders'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'User.Users'
        ]);
        $this->hasMany('ShopCustomerAddresses', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomerAddresses'
        ]);
        $this->hasMany('ShopCustomerDiscounts', [
            'foreignKey' => 'shop_customer_id',
            'className' => 'Shop.ShopCustomerDiscounts'
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
            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email');
            //->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->allowEmpty('first_name');

        $validator
            ->allowEmpty('last_name');

        $validator
            ->add('is_guest', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_guest');

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
        //$rules->add($rules->isUnique(['email']));
        return $rules;
    }

    /**
     * Create shop customer from given user id
     *
     * @param $userId
     * @param bool|true $save
     * @return bool|\Cake\Datasource\EntityInterface|Entity|mixed
     */
    public function createFromUserId($userId, $save = true)
    {
        $user = $this->Users->get($userId, ['contain' => []]);

        return $this->createFromUser($user, [], $save);
    }

    /**
     * Create shop customer from given user entity
     *
     * @param EntityInterface $user
     * @param array $data
     * @param bool|true $save
     * @return bool|EntityInterface|Entity|mixed
     */
    public function createFromUser(EntityInterface $user, array $data = [], $save = true)
    {
        // check if customer with email already exists
        $customer = $this->find()->where(['email' => $user->get('email')])->first();
        if (!$customer) {
            $customer = $this->newEntity();
        }

        $customer->user_id = $user->get('id');
        $customer->email = $user->get('email');

        $customer->accessible(['user_id', 'email'], false);
        $customer = $this->patchEntity($customer, $data);
        if ($save === true) {
            $customer = $this->save($customer);
        }

        return $customer;
    }
}
