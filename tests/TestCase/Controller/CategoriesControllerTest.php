<?php

namespace Shop\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Class CategoriesControllerTest
 *
 * @package Shop\Test\TestCase\Controller
 */
class CategoriesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        //'plugin.content.page_metas',
        //'plugin.media.media_attachments',
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
        'plugin.Shop.ShopCategories',
        'plugin.Shop.ShopProducts',
        'plugin.Shop.ShopCountries',
        'plugin.Shop.ShopTags',
        'plugin.Shop.ShopCategoriesTags',
        //'plugin.Shop.billing_address',
        //'plugin.Shop.shipping_address'
        'plugin.User.Users',
        'plugin.User.Groups',
        'plugin.User.GroupsUsers',
        //'plugin.Content.ContentModules',
        //'plugin.Content.Modules',
    ];

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        Configure::write('Shop.Categories.layout', false);
        Configure::write('Shop.Router.enablePrettyUrls', false);

        TableRegistry::getTableLocator()->get('Shop.ShopCategories')->behaviors()->unload('Media');
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->_setupPrettyRoutes(false);
    }

    /**
     * Enable pretty urls in config
     *
     * @param bool|true $enable
     */
    protected function _setupPrettyRoutes($enable = true)
    {
        Configure::write('Shop.Router.enablePrettyUrls', $enable);
    }

    /**
     * Test index action
     */
    public function testIndex()
    {
        $this->markTestIncomplete();
        $this->get('/shop/categories');
        $this->assertResponseOk();

        $this->markTestIncomplete();
        $this->assertRedirect('/shop/categories/1');
    }

    /**
     * Test view action
     */
    public function testView()
    {
        $this->markTestIncomplete();
        $this->get('/shop/categories/view/1');
        $this->assertResponseOk();
    }

    /**
     * Test view action
     */
    public function testViewWithPrettyUrls()
    {
        $this->markTestIncomplete();
        $this->_setupPrettyRoutes();

        $this->get('/shop/test-root-category/1');
        $this->assertResponseOk();
    }
}
