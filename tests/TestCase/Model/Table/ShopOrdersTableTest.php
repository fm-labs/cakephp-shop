<?php
namespace Shop\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Shop\Model\Table\ShopOrdersTable Test Case
 */
class ShopOrdersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Shop\Model\Table\ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.shop.shop_orders',
        'plugin.shop.shop_customers',
        //'plugin.shop.shop_addresses',
        //'plugin.shop.users',
        //'plugin.shop.primary_group',
        //'plugin.shop.primary_users',
        //'plugin.shop.groups',
        //'plugin.shop.user_groups_users',
        //'plugin.shop.shop_carts',
        'plugin.shop.shop_order_items',
        'plugin.shop.shop_order_addresses',
        //'plugin.shop.shop_customer_addresses',
        //'plugin.shop.countries',
        //'plugin.shop.billing_address',
        //'plugin.shop.shipping_address'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ShopOrders') ? [] : ['className' => 'Shop\Model\Table\ShopOrdersTable'];
        $this->ShopOrders = TableRegistry::get('ShopOrders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShopOrders);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationBilling method
     *
     * @return void
     */
    public function testValidationBilling()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationShipping method
     *
     * @return void
     */
    public function testValidationShipping()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationPayment method
     *
     * @return void
     */
    public function testValidationPayment()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationPaymentCreditCardInternal method
     *
     * @return void
     */
    public function testValidationPaymentCreditCardInternal()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationSubmit method
     *
     * @return void
     */
    public function testValidationSubmit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }


    /**
     * Test getNextOrderNr method
     *
     * @return void
     */
    public function testGetNextOrderNr()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test calculate method
     *
     * @return void
     */
    public function testCalculate()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test submit method
     *
     * @return void
     */
    public function testSubmit()
    {

        $order = $this->ShopOrders->get(1);

        $result = $this->ShopOrders->submit($order);

        $this->assertEquals(ShopOrdersTable::ORDER_STATUS_SUBMITTED, $result->status);
        $this->assertNotEmpty($order->submitted);
        $this->assertNotEmpty($order->shop_customer_id);
        $this->assertNotEmpty($order->billing_address_id);
        $this->assertNotEmpty($order->shipping_address_id);
        $this->assertNotEmpty($order->nr);
    }

    /**
     * Test updateOrderStatus method
     *
     * @return void
     */
    public function testUpdateOrderStatus()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test assignOrderNr method
     *
     * @return void
     */
    public function testAssignOrderNr()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
