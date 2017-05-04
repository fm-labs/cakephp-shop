<?php
namespace Shop\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\I18n\Time;
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
        'plugin.shop.shop_customer_addresses',
        //'plugin.shop.shop_addresses',
        //'plugin.shop.users',
        //'plugin.shop.primary_group',
        //'plugin.shop.primary_users',
        //'plugin.shop.groups',
        //'plugin.shop.user_groups_users',
        //'plugin.shop.shop_carts',
        'plugin.shop.shop_order_items',
        'plugin.shop.shop_order_addresses',
        'plugin.shop.shop_countries',
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

        // use custom ordergroup for testing
        Configure::write('Shop.Order.nrStart', 1000);
        Configure::write('Shop.Order.nrGroup', 'test');

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
     * Test findOrder method
     */
    public function testFindOrder()
    {
        $result = $this->ShopOrders->find('order');
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrder', $result);
    }


    /**
     * Test setOrderAddress method
     */
    public function testSetOrderAddress()
    {
        $addressData = [
            'first_name' => 'Testme',
            'last_name' => 'Testme',
            'street' => 'Teststreet 1',
            'zipcode' => '1111',
            'city' => 'Test',
            'country_id' => 1
        ];
        $address = $this->ShopOrders->ShopOrderAddresses->newEntity($addressData);

        $order = $this->ShopOrders->get(1);
        $result = $this->ShopOrders->setOrderAddress($order, $address, 'B');
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $result);

        // check order integrity
        $order = $this->ShopOrders->get(1);
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $order->getBillingAddress());
        $this->assertArraySubset($addressData, $order->getBillingAddress()->toArray());

        // if billing address is already set, patch billing address entry rather than creating a new record
        $billingAddressId = $order->getBillingAddress()->id;
        $addressData = [
            'first_name' => 'Testme2',
            'last_name' => 'Testme',
            'street' => 'Teststreet 2',
            'zipcode' => '1111',
            'city' => 'Test',
            'country_id' => 1
        ];
        $address = $this->ShopOrders->ShopOrderAddresses->newEntity($addressData);
        $order = $this->ShopOrders->get(1);
        $result = $this->ShopOrders->setOrderAddress($order, $address, 'B');
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $result);
        $this->assertArraySubset($addressData, $result->toArray());
        $this->assertArraySubset($addressData, $order->getBillingAddress()->toArray());
        $this->assertEquals($billingAddressId, $order->getBillingAddress()->id);
    }

    /**
     * Test setOrderAddressFromCustomerAddress method
     */
    public function testSetOrderAddressFromCustomerAddress()
    {
        $orderId = 1;
        $customerAddressId = 1;
        $customerAddress = $this->ShopOrders->ShopOrderAddresses->ShopCustomerAddresses->get($customerAddressId);
        $order = $this->ShopOrders->get($orderId);
        $result = $this->ShopOrders->setOrderAddressFromCustomerAddress($order, $customerAddress, 'B');

        // check if customer address is referenced
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $result);
        $this->assertEquals('B', $result->type);
        $this->assertEquals($orderId, $result->shop_order_id);
        $this->assertEquals($customerAddressId, $result->shop_customer_address_id);

        // check address data
        $expected = $customerAddress->extract(['first_name', 'last_name', 'street', 'zipcode', 'country_id']);
        $this->assertArraySubset($expected, $result->toArray());
    }

    /**
     * Test setOrderAddressFromCustomerAddress method
     */
    public function testSetOrderAddressFromCustomerAddressById()
    {
        $orderId = 1;
        $customerAddressId = 1;
        $order = $this->ShopOrders->get($orderId);
        $result = $this->ShopOrders->setOrderAddressFromCustomerAddress($order, $customerAddressId, 'B');

        // check if customer address is referenced
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $result);
        $this->assertEquals('B', $result->type);
        $this->assertEquals($orderId, $result->shop_order_id);
        $this->assertEquals($customerAddressId, $result->shop_customer_address_id);

        // check address data
        $customerAddress = $this->ShopOrders->ShopOrderAddresses->ShopCustomerAddresses->get($customerAddressId);
        $expected = $customerAddress->extract(['first_name', 'last_name', 'street', 'zipcode', 'country_id']);
        $this->assertArraySubset($expected, $result->toArray());
    }


    /**
     * Test getNextOrderNr method
     *
     * @return void
     */
    public function testGetNextOrderNr()
    {
        $this->assertEquals(1000, $this->ShopOrders->getNextOrderNr());
        $this->assertEquals(1000, $this->ShopOrders->getNextOrderNr('test2'));

        // add some dummy entries
        foreach(['test', 'test2'] as $ordergroup) {
            for ($i = 1; $i < 3; $i++) {
                $order = $this->ShopOrders->newEntity([
                    'is_temporary' => false,
                    'submitted' => Time::now(),
                    'ordergroup' => $ordergroup,
                    'nr' => 1000 + $i
                ], ['validate' => false]);
                if (!$this->ShopOrders->save($order)) {
                    $this->fail('Failed to inject test data');
                }
            }
        }

        $this->assertEquals(1003, $this->ShopOrders->getNextOrderNr());
        $this->assertEquals(1003, $this->ShopOrders->getNextOrderNr('test'));
        $this->assertEquals(1003, $this->ShopOrders->getNextOrderNr('test2'));
    }

    /**
     * Test assignOrderNr method
     *
     * @return void
     */
    public function testAssignOrderNr()
    {
        $order = $this->ShopOrders->get(1);
        $result = $this->ShopOrders->assignOrderNr($order);

        $this->assertEquals(1000, $result->nr);
        $this->assertEquals('test', $result->ordergroup);
    }


    /**
     * Test updateOrderStatus method
     *
     * @return void
     */
    public function testUpdateOrderStatus()
    {
        $order = $this->ShopOrders->get(1);

        $result = $this->ShopOrders->updateOrderStatus($order, ShopOrdersTable::ORDER_STATUS_SUBMITTED);
        $this->assertEquals($result->status, ShopOrdersTable::ORDER_STATUS_SUBMITTED);
    }

    /**
     * Test submit method
     *
     * @return void
     */
    public function testSubmitOrder()
    {
        $order = $this->ShopOrders->get(1);
        $result = $this->ShopOrders->submitOrder($order);

        $this->assertEquals(ShopOrdersTable::ORDER_STATUS_SUBMITTED, $result->status);
        $this->assertNotEmpty($order->submitted);
        $this->assertNotEmpty($order->shop_customer_id);
        $this->assertEquals(1000, $order->nr);
        $this->assertEquals('test', $order->ordergroup);
        $this->assertEquals(1, $order->status);
        $this->assertEquals(false, $order->is_temporary);
    }


    /**
     * Test validationDefault method
     *
     * @return void
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
*/

    /**
     * Test validationPayment method
     *
     * @return void
    public function testValidationPayment()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
*/

    /**
     * Test validationPaymentCreditCardInternal method
     *
     * @return void
    public function testValidationPaymentCreditCardInternal()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
*/

    /**
     * Test validationSubmit method
     *
     * @return void
    public function testValidationSubmit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
*/


    /**
     * Test calculate method
     *
     * @return void
    public function testCalculate()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
*/
}
