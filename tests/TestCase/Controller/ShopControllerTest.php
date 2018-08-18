<?php

namespace Shop\tests\TestCase\Controller;


use Cake\TestSuite\IntegrationTestCase;

class ShopControllerTest extends IntegrationTestCase
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
        //'plugin.shop.shop_order_transactions',
        //'plugin.shop.shop_order_transaction_notifies',
        'plugin.shop.shop_products',
        'plugin.shop.shop_categories',
        'plugin.shop.shop_countries',
        //'plugin.shop.billing_address',
        //'plugin.shop.shipping_address'
        'plugin.user.users',
        'plugin.user.groups',
        'plugin.user.groups_users',
        'plugin.content.content_modules',
        'plugin.content.modules',
    ];

    public function testIndex()
    {
        $this->get('/shop');
        $this->assertResponseOk();
    }
}