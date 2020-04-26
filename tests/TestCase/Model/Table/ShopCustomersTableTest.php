<?php
declare(strict_types=1);

namespace Shop\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Shop\Model\Table\ShopCustomersTable Test Case
 */
class ShopCustomersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Shop\Model\Table\ShopCustomersTable
     */
    public $ShopCustomers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Shop.ShopCustomers',
        'plugin.Shop.ShopOrders',
        'plugin.Shop.ShopOrderItems',
        'plugin.Shop.ShopOrderAddresses',
        'plugin.Shop.ShopCustomerAddresses',
        'plugin.Shop.ShopCountries',
        'plugin.User.Users',
        'plugin.User.Groups',
        //'plugin.Shop.user_groups_users'
    ];

    protected $_testUserData = [
        'name'          => 'Test',
        'username'      => 'foo@example.org',
        'email'         => 'foo@example.org',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ShopCustomers') ? [] : ['className' => 'Shop\Model\Table\ShopCustomersTable'];
        $this->ShopCustomers = TableRegistry::getTableLocator()->get('ShopCustomers', $config);
    }

    protected function _createUser($save = true)
    {

        $user = $this->ShopCustomers->Users->newEmptyEntity();
        $user->setAccess('*', true);
        $user = $this->ShopCustomers->Users->patchEntity($user, $this->_testUserData, ['validate' => false]);

        if (!$save) {
            return $user;
        }

        $user = $this->ShopCustomers->Users->save($user);
        if (!$user || !$user->id) {
            debug($user->getErrors());
            $this->fail('Failed to create test user');
        }

        return $user;
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ShopCustomers);

        parent::tearDown();
    }

    public function testTestDataIntegrity()
    {
        $this->markTestIncomplete();

        $expected = $this->_testUserData;

        $user = $this->_createUser();
        $this->assertEquals($expected['email'], $user->email);
        $this->assertNotEmpty($user->id);

        $this->markTestIncomplete("Check name strings in user and customer profile");
    }

    /**
     * Test createFromUser method
     *
     * @return void
     */
    public function testCreateFromUser()
    {
        $this->markTestIncomplete();

        $user = $this->_createUser();

        $result = $this->ShopCustomers->createFromUser($user);

        $expected = $this->_testUserData;
        $this->assertEquals($expected['email'], $result->email);
        $this->assertEquals($user->id, $result->user_id);

        $this->markTestIncomplete("Check name strings in user and customer profile");
    }
}
