<?php

namespace Shop\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutControllerTest
 *
 * @package Shop\Test\TestCase\Controller
 */
class CheckoutControllerTest extends IntegrationTestCase
{

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
        'plugin.shop.shop_products',
        'plugin.shop.shop_countries',
        //'plugin.shop.billing_address',
        //'plugin.shop.shipping_address'
        'plugin.user.users',
        'plugin.user.groups',
        'plugin.user.groups_users',
        'plugin.content.content_modules',
        'plugin.content.modules',
    ];

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->Users = TableRegistry::getTableLocator()->get('User.Users');

        Configure::delete('Shop.Checkout.Steps');
        Configure::write('Shop.Checkout.Steps', [
            'customer' => [
                'className' => 'Shop.Customer'
            ],
            'shipping_address' => [
                'className' => 'Shop.ShippingAddress'
            ],
            'shipping' => [
                'className' => 'Shop.Shipping'
            ],
            'billing_address' => [
                'className' => 'Shop.BillingAddress'
            ],
            'payment' => [
                'className' => 'Shop.Payment'
            ],
            'submit' => [
                'className' => 'Shop.Submit'
            ],
        ]);

        Configure::delete('Shipping.Engines');
        Configure::write('Shipping.Engines', [
            'custom' => [
                'className' => 'Shop.CustomRate',
                'enabled' => true,
                'name' => 'Standard Versand',
            ],
            'fixed' => [
                'className' => 'Shop.FixedRate',
                'enabled' => false,
                'name' => 'Standard Versand Express',
                'cost' => 10.00
            ],
        ]);

        Configure::delete('Payment.Engines');
        Configure::write('Payment.Engines', [
            'credit_card_internal' => [
                'className' => 'Shop.CreditCardInternal',
                'name' => 'Kreditkarte',
                //'desc' => '',
                //'logoUrl' => '',
                'guest' => true,
                'enabled' => true,
            ],
            'payment_slip' => [
                'className' => 'Shop.PaymentSlip',
                'name' => 'Rechnung mit Erlagschein',
                //'desc' => null,
                //'logoUrl' => '',
                'guest' => false,
                'enabled' => true,
            ]
        ]);
    }

    private function _setupCart($orderId = 1, array $orderData = [])
    {
        $order = $this->ShopOrders->get($orderId, ['contain' => ['ShopCustomers' => ['Users'], 'ShopOrderItems', 'ShopOrderAddresses']]);
        foreach ($orderData as $k => $v) {
            $order->$k = $v;
        }

        $session = ['Shop' => [
            'Cart' => ['id' => $order->cartid],
            'Customer' => ($order->shop_customer) ? $order->shop_customer->toArray() : null,
            'Order' => $order
        ]];
        $this->session($session);

        return $order;
    }

    protected function _setupAuthSession($userId = 2)
    {
        $user = $this->Users->find('authUser')->where(['id' => $userId]);

        $this->session([
            'Auth' => [
                'User' => $user->toArray()
            ]
        ]);
    }

    /**
     * Test checkout with empty cart
     * @return void
     */
    public function testCheckoutWithEmptyCart()
    {
        $this->get('/shop/checkout/index');
        $this->assertRedirect(['controller' => 'Cart', 'action' => 'index']);
    }

    /**
     * Test checkout
     * @return void
     */
    public function testCheckout()
    {
        $order = $this->_setupCart(2);

        $this->get('/shop/checkout/index/' . $order->cartid);
        $this->assertRedirect(['controller' => 'Checkout', 'action' => 'customer', $order->cartid]);
    }

    /**
     * Test customer signup during checkout process
     * @return void
     */
    public function testCustomerSignup()
    {
        $order = $this->_setupCart(2);

        $usersCount = TableRegistry::getTableLocator()->get('User.Users')->find()->count();
        $customersCount = TableRegistry::getTableLocator()->get('Shop.ShopCustomers')->find()->count();
        $expectedCustomerId = $customersCount + 1;

        // POST register form to customer step
        $this->post('/shop/checkout/customer/' . $order->cartid, [
            'op' => 'signup',
            'first_name' => 'Super',
            'last_name' => 'Mario',
            'email' => 'supermario@example.org',
            'password1' => 'testtest',
            'password2' => 'testtest'
        ]);

        // expects a new & authenticated user
        $this->assertSession($usersCount + 1, 'Auth.User.id');
        $this->assertSession(1, 'Auth.User.login_enabled');
        // expects a new shop customer profile
        $this->assertSession($expectedCustomerId, 'Shop.Customer.id');
        // expects customer id set in order
        $this->assertSession($expectedCustomerId, 'Shop.Order.shop_customer_id');
        // expects the order has been calculated
        $this->assertSession(100, 'Shop.Order.items_value_net');
        // expexts order status to be unchanged
        $this->assertSession(0, 'Shop.Order.status');
        // expects to be redirect to the next (billing) step
        $this->assertRedirect(['controller' => 'Checkout', 'action' => 'shipping_address', $order->cartid]);
    }

    /**
     * Test customer login during checkout process
     * @return void
     */
    public function testCustomerLogin()
    {
        $order = $this->_setupCart(2);

        // Normal user from User plugin
        $this->Users = TableRegistry::getTableLocator()->get('User.Users');
        $user = $this->Users->get(2);

        // Workaround: need to set password a new password with actual password hasher instance
        // (might depend on security salt)
        $password = 'testtest';
        $user->password = $password;
        $user = $this->Users->save($user);
        if (!$user) {
            $this->fail('Failed to set user password');
        }

        // Check if we prepared a customer profile for that user
        $customer = TableRegistry::getTableLocator()->get('Shop.ShopCustomers')->find()->where(['user_id' => $user->id])->first();
        if (!$customer) {
            $this->fail('No test customer found for test user with id ' . $user->id);
        }

        // POST register form to customer step
        $this->post('/shop/checkout/customer/' . $order->cartid, [
            'op' => 'login',
            //'email' => 'test@example.org',
            'username' => 'test',
            'password' => $password,
        ]);

        // expects a new & authenticated user
        $this->assertSession($user->id, 'Auth.User.id');
        // expects a new shop customer profile
        $this->assertSession($customer->id, 'Shop.Customer.id');
        // expects customer id set in order
        $this->assertSession($customer->id, 'Shop.Order.shop_customer_id');
        // expects the order has been calculated
        $this->assertSession(100, 'Shop.Order.items_value_net');
        // expexts order status to be unchanged
        $this->assertSession(0, 'Shop.Order.status');
        // expects to be redirect to the next (billing) step
        $this->assertRedirect(['controller' => 'Checkout', 'action' => 'shipping_address', $order->cartid]);
    }

    /**
     * Test checkout shipping address step
     * @return void
     */
    public function testShippingAddress()
    {
        $this->ShopOrders->updateAll(['shop_customer_id' => 1], ['id' => 2]);

        $this->ShopCustomers = TableRegistry::getTableLocator()->get('Shop.ShopCustomers');
        $customer = $this->ShopCustomers->get(1, ['contain' => 'Users']);

        // setup cart order
        $order = $this->_setupCart(2);

        $this->session(['Auth.User' => $customer->user->toArray()]);
        $this->session(['Shop.Customer' => $customer->toArray()]);

        $this->get('/shop/checkout/shipping-address/' . $order->cartid);

        $this->assertNoRedirect();
        $this->assertSession('shipping_address', 'Shop.Checkout.Step.id');

        $this->markTestIncomplete('testShippingAddress: POST requests not implemented yet');
    }
}
