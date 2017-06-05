<?php
namespace Shop\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Shop\Model\Table\ShopCustomersTable;

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
        'plugin.shop.shop_customers',
        'plugin.shop.shop_orders',
        'plugin.shop.shop_order_items',
        'plugin.shop.shop_order_addresses',
        'plugin.shop.shop_customer_addresses',
        'plugin.shop.shop_countries',
        'plugin.user.users',
        'plugin.user.groups',
        //'plugin.shop.user_groups_users'
    ];

    protected $_testUserData = [
        'name'          => 'Test',
        'username'      => 'foo@example.org',
        'email'         => 'foo@example.org'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ShopCustomers') ? [] : ['className' => 'Shop\Model\Table\ShopCustomersTable'];
        $this->ShopCustomers = TableRegistry::get('ShopCustomers', $config);
    }

    protected function _createUser($save = true)
    {

        $user = $this->ShopCustomers->Users->newEntity();
        $user->accessible('*', true);
        $user = $this->ShopCustomers->Users->patchEntity($user, $this->_testUserData,['validate' => false]);

        if (!$save) {
            return $user;
        }

        $user = $this->ShopCustomers->Users->save($user);
        if (!$user || !$user->id) {
            debug($user->errors());
            $this->fail('Failed to create test user');
        }
        return $user;
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShopCustomers);

        parent::tearDown();
    }

    public function testTestDataIntegrity()
    {
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
        $user = $this->_createUser();

        $result = $this->ShopCustomers->createFromUser($user);

        $expected = $this->_testUserData;
        $this->assertEquals($expected['email'], $result->email);
        $this->assertEquals($user->id, $result->user_id);

        $this->markTestIncomplete("Check name strings in user and customer profile");
    }
}
