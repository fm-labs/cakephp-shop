<?php

namespace Shop\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ShopControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
        //'plugin.Shop.ShopOrderTransactions',
        //'plugin.Shop.ShopOrderTransactionNotifies',
        'plugin.Shop.ShopProducts',
        'plugin.Shop.ShopCategories',
        'plugin.Shop.ShopCountries',
        //'plugin.Shop.billing_address',
        //'plugin.Shop.shipping_address'
        'plugin.User.Users',
        'plugin.User.Groups',
        'plugin.User.GroupsUsers',
        'plugin.Content.ContentModules',
        'plugin.Content.Modules',
    ];

    public function testIndex()
    {
        $this->get('/shop');
        $this->assertResponseOk();
    }
}
