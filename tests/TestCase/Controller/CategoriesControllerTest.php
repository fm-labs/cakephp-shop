<?php

namespace Shop\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * Class CategoriesControllerTest
 *
 * @package Shop\Test\TestCase\Controller
 */
class CategoriesControllerTest extends IntegrationTestCase
{


    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.content.page_metas',
        'plugin.media.media_attachments',
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
        'plugin.shop.shop_categories',
        'plugin.shop.shop_products',
        'plugin.shop.shop_countries',
        'plugin.shop.shop_tags',
        'plugin.shop.shop_categories_tags',
        //'plugin.shop.billing_address',
        //'plugin.shop.shipping_address'
        'plugin.user.users',
        'plugin.user.groups',
        'plugin.user.groups_users',
        //'plugin.content.content_modules',
        //'plugin.content.modules',
    ];

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        Configure::write('Shop.Categories.layout', false);
        Configure::write('Shop.Router.enablePrettyUrls', false);

        TableRegistry::get('Shop.ShopCategories')->behaviors()->unload('Media');
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
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

        $this->markTestIncomplete();
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
