<?php
namespace Shop\Model\Table;

use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Shop\Model\Entity\ShopCustomer;

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
     * @var int Minimum length of passwords
     */
    public static $minPasswordLength = 8;

    public static $passwordRegex = '/^(\w)+$/';

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
            ->allowEmpty('password');

        $validator
            ->allowEmpty('first_name');

        $validator
            ->allowEmpty('last_name');

        $validator
            ->allowEmpty('email_verification_code');

        $validator
            ->add('email_verified', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('email_verified');

        $validator
            ->add('is_guest', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_guest');

        $validator
            ->add('is_blocked', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('is_blocked');

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
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }


    /**
     * Validation rules for adding new users
     *
     * @param Validator $validator
     * @return Validator
     */
    public function validationAdd(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')

            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name')

            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name')

            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email')

            ->add('password1', 'password', [
                'rule' => 'validateNewPassword1',
                'provider' => 'table',
                'message' => __d('shop','Invalid password')
            ])
            ->requirePresence('password1', 'create')
            ->notEmpty('password1')

            ->add('password2', 'password', [
                'rule' => 'validateNewPassword2',
                'provider' => 'table',
                'message' => __d('shop','Passwords do not match')
            ])
            ->requirePresence('password2', 'create')
            ->notEmpty('password2');


        return $validator;
    }



    /**
     * Validation rules for adding new users
     *
     * @param Validator $validator
     * @return Validator
     */
    public function validationAddGuest(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')

            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name')

            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name')

            ->add('email', 'valid', ['rule' => 'email'])
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        return $validator;
    }

    public function createFromUserId($userId, $save = true)
    {
        $user = $this->Users->get($userId);

        $customer = $this->newEntity();
        $customer->first_name = null;
        $customer->last_name = null;
        $customer->user_id = $user->id;
        $customer->email = $user->email;

        if ($save === true) {
            $customer = $this->save($customer);
        }

        return $customer;
    }

    /**
     * Add new user
     *
     * @return \Cake\Datasource\EntityInterface|Entity
     */
    public function add(array $data)
    {
        $customer = $this->newEntity();
        $customer->accessible(['password1', 'password2'], true);
        $customer = $this->patchEntity($customer, $data, ['validate' => 'add']);
        if ($customer->errors()) {
            return $customer;
        }

        if ($this->save($customer)) {
            Log::info('Added shop customer with ID ' . $customer->id);
            $this->_syncUser($customer, [
                'name' => $customer->display_name,
                'username' => $customer->email,
                'login_enabled' => true,
                'password' => $customer->password1
            ]);
        }
        return $customer;
    }

    protected function _syncUser(ShopCustomer &$customer, array $userData = [])
    {
        if (!$customer->user_id) {
            $user = $this->Users->newEntity();
            $user->name = $customer->email;
            $user->username = $customer->email;
            $user->email = $customer->email;
            $user->login_enabled = true;

            $user->accessible('*', true);
            $user = $this->Users->patchEntity($user, $userData);
            $user->accessible('*', false);


            if (!$this->Users->save($user)) {
                debug($user->errors());
                Log::error('ShopCustomers user sync failed');
                return;
            }

            Log::info('ShopCustomers user sync linked CustomerID ' . $customer->id . ' to UserID ' . $user->id);
            $customer->user_id = $user->id;
            $this->save($customer);
        }
        elseif (!empty($userData)) {
            $user = $this->Users->get($customer->user_id);

            $user->accessible('*', true);
            $user = $this->Users->patchEntity($user, $userData);
            $user->accessible('*', false);

            if (!$this->Users->save($user)) {
                debug($user->errors());
                Log::error('ShopCustomers user sync failed');
                return;
            }

            Log::info('ShopCustomers user sync linked CustomerID ' . $customer->id . ' to UserID ' . $user->id);
        }
    }

    public function addGuest(ShopCustomer $customer)
    {

        $validate = 'addGuest';
        $customer->accessible(['password1', 'password2'], false);
        $customer->accessible('password', false);
    }

    /**
     * Password Validation Rule
     *
     * @param $value
     * @param $context
     * @return bool|string
     */
    public function validateNewPassword1($value, $context)
    {
        $value = trim($value);

        // Check password length
        if (strlen($value) < static::$minPasswordLength) {
            return __d('shop','Password too short. Minimum {0} characters', static::$minPasswordLength);
        }

        // Check for illegal characters
        if (!preg_match(static::$passwordRegex, $value)) {
            return __d('shop','Password contains illegal characters');
        }

        return true;
    }

    /**
     * Password Verification Validation Rule
     * @param $value
     * @param $context
     * @return bool
     */
    public function validateNewPassword2($value, $context)
    {
        $value = trim($value);

        if (!isset($context['data']['password1'])) {
            return false;
        }

        if ($context['data']['password1'] === $value) {
            return true;
        }

        return __d('shop','The passwords do not match');
    }

}
