<?php
declare(strict_types=1);

namespace Shop\Test\TestCase\Model\Table;

use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Shop\Model\Table\ShopOrdersTable;
use Shop\Service\CustomerService;
use Shop\Service\EmailNotificationService;

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
        'plugin.Shop.ShopOrders',
        'plugin.Shop.ShopCustomers',
        'plugin.Shop.ShopCustomerAddresses',
        //'plugin.Shop.shop_addresses',
        //'plugin.Shop.users',
        //'plugin.Shop.primary_group',
        //'plugin.Shop.primary_users',
        //'plugin.Shop.groups',
        //'plugin.Shop.user_groups_users',
        //'plugin.Shop.shop_carts',
        'plugin.Shop.ShopOrderItems',
        'plugin.Shop.ShopOrderAddresses',
        'plugin.Shop.ShopCountries',
        //'plugin.Shop.billing_address',
        //'plugin.Shop.shipping_address'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ShopOrders') ? [] : ['className' => 'Shop\Model\Table\ShopOrdersTable'];
        $this->ShopOrders = TableRegistry::getTableLocator()->get('ShopOrders', $config);
        $this->ShopOrders->getEventManager()->on(new CustomerService());
        $this->ShopOrders->getEventManager()->on(new EmailNotificationService());

        // use custom ordergroup for testing
        Configure::write('Shop.Order.nrStart', 1000);
        Configure::write('Shop.Order.nrGroup', 'test');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
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
            'country_id' => 1,
        ];
        $address = $this->ShopOrders->ShopOrderAddresses->newEntity($addressData);

        $order = $this->ShopOrders->get(1);
        $result = $this->ShopOrders->setOrderAddress($order, $address, 'B');
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $result);

        // check order integrity
        $order = $this->ShopOrders->get(1, ['contain' => ['BillingAddresses', 'ShippingAddresses']]);
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $order->getBillingAddress());
        $this->assertArrayHasKey('first_name', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('last_name', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('street', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('zipcode', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('city', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('country_id', $order->getBillingAddress()->toArray());
        $this->assertSame('Testme', $order->getBillingAddress()->toArray()['first_name']);
        $this->assertSame('Testme', $order->getBillingAddress()->toArray()['last_name']);
        $this->assertSame('Teststreet 1', $order->getBillingAddress()->toArray()['street']);
        $this->assertSame('1111', $order->getBillingAddress()->toArray()['zipcode']);
        $this->assertSame('Test', $order->getBillingAddress()->toArray()['city']);
        $this->assertSame(1, $order->getBillingAddress()->toArray()['country_id']);

        // if billing address is already set, patch billing address entry rather than creating a new record
        $billingAddressId = $order->getBillingAddress()->id;
        $addressData = [
            'first_name' => 'Testme2',
            'last_name' => 'Testme',
            'street' => 'Teststreet 2',
            'zipcode' => '1111',
            'city' => 'Test',
            'country_id' => 1,
        ];
        $address = $this->ShopOrders->ShopOrderAddresses->newEntity($addressData);
        $order = $this->ShopOrders->get(1, ['contain' => ['BillingAddresses', 'ShippingAddresses']]);
        $result = $this->ShopOrders->setOrderAddress($order, $address, 'B');
        $this->assertInstanceOf('Shop\\Model\\Entity\\ShopOrderAddress', $result);
        $this->assertArrayHasKey('first_name', $result->toArray());
        $this->assertArrayHasKey('last_name', $result->toArray());
        $this->assertArrayHasKey('street', $result->toArray());
        $this->assertArrayHasKey('zipcode', $result->toArray());
        $this->assertArrayHasKey('city', $result->toArray());
        $this->assertArrayHasKey('country_id', $result->toArray());
        $this->assertSame('Testme2', $result->toArray()['first_name']);
        $this->assertSame('Testme', $result->toArray()['last_name']);
        $this->assertSame('Teststreet 2', $result->toArray()['street']);
        $this->assertSame('1111', $result->toArray()['zipcode']);
        $this->assertSame('Test', $result->toArray()['city']);
        $this->assertSame(1, $result->toArray()['country_id']);

        $order = $this->ShopOrders->get(1, ['contain' => ['BillingAddresses', 'ShippingAddresses']]);
        $this->assertArrayHasKey('first_name', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('last_name', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('street', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('zipcode', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('city', $order->getBillingAddress()->toArray());
        $this->assertArrayHasKey('country_id', $order->getBillingAddress()->toArray());
        $this->assertSame('Testme2', $order->getBillingAddress()->toArray()['first_name']);
        $this->assertSame('Testme', $order->getBillingAddress()->toArray()['last_name']);
        $this->assertSame('Teststreet 2', $order->getBillingAddress()->toArray()['street']);
        $this->assertSame('1111', $order->getBillingAddress()->toArray()['zipcode']);
        $this->assertSame('Test', $order->getBillingAddress()->toArray()['city']);
        $this->assertSame(1, $order->getBillingAddress()->toArray()['country_id']);
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
        foreach (['test', 'test2'] as $ordergroup) {
            for ($i = 1; $i < 3; $i++) {
                $order = $this->ShopOrders->newEntity([
                    'is_temporary' => false,
                    'submitted' => Time::now(),
                    'ordergroup' => $ordergroup,
                    'nr' => 1000 + $i,
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

        $result = $this->ShopOrders->updateOrderStatus($order, ShopOrdersTable::ORDER_STATUS_PENDING);
        $this->assertEquals($result->status, ShopOrdersTable::ORDER_STATUS_PENDING);
    }

    /**
     * Test submit method
     *
     * @return void
     */
    public function testSubmitOrder()
    {

        // test without agree_terms
        $order = $this->ShopOrders->find('order', ['ShopOrders.id' => 1]);
        $result = $this->ShopOrders->submitOrder($order, ['agree_terms' => 0]);
        $this->assertNotEmpty($order->getErrors());
        $this->assertArrayHasKey('agree_terms', $order->getErrors());
        $this->assertArrayHasKey('checked', $order->errors('agree_terms'));

        // test with agree_term
        $order = $this->ShopOrders->find('order', ['ShopOrders.id' => 1]);
        $result = $this->ShopOrders->submitOrder($order, ['agree_terms' => 1]);
        $this->assertEquals(ShopOrdersTable::ORDER_STATUS_PENDING, $result->status);
        $this->assertNotEmpty($order->submitted);
        $this->assertNotEmpty($order->shop_customer_id);
        $this->assertEquals(1000, $order->nr);
        $this->assertEquals('test', $order->ordergroup);
        $this->assertEquals(ShopOrdersTable::ORDER_STATUS_PENDING, $order->status);
        $this->assertEquals(false, $order->is_temporary);
        $this->assertEquals(true, $order->agree_terms);

        $billingAddress = $order->getBillingAddress();
        $this->assertNotEmpty($billingAddress);

        $this->markTestIncomplete('Test if customer address has been created from billing address');
        //$ShopCustomerAddresses = TableRegistry::getTableLocator()->get('Shop.ShopCustomerAddresses');
        //$customerAddress = $ShopCustomerAddresses->find()->where($billingAddress->extractAddress())->first();
        //$this->assertNotNull($customerAddress);
    }

/**
 * Test validationDefault method
 *
 * @return void
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    } */

/**
 * Test validationPayment method
 *
 * @return void
    public function testValidationPayment()
    {
        $this->markTestIncomplete('Not implemented yet.');
    } */

/**
 * Test validationPaymentCreditCardInternal method
 *
 * @return void
    public function testValidationPaymentCreditCardInternal()
    {
        $this->markTestIncomplete('Not implemented yet.');
    } */

/**
 * Test validationSubmit method
 *
 * @return void
    public function testValidationSubmit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    } */

/**
 * Test calculate method
 *
 * @return void
    public function testCalculate()
    {
        $this->markTestIncomplete('Not implemented yet.');
    } */
}
