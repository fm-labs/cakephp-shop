<?php

namespace Shop;

use Backend\Backend;
use Backend\BackendPluginInterface;
use Backend\Event\RouteBuilderEvent;
use Backend\View\BackendView;
use Banana\Application;
use Banana\Menu\Menu;
use Banana\Plugin\BasePlugin;
use Banana\Plugin\PluginInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Settings\SettingsManager;
use Shop\Service\OrderNotificationService;

/**
 * Class ShopPlugin
 *
 * @package Shop
 */
class Plugin extends BasePlugin implements EventListenerInterface
{
    public function bootstrap(PluginApplicationInterface $app)
    {
        parent::bootstrap($app);

        $eventManager = EventManager::instance();
        $eventManager->on(new \Shop\Service\CartService());
        $eventManager->on(new \Shop\Service\ShopRulesService());
        $eventManager->on(new \Shop\Service\CustomerService());
        $eventManager->on(new \Shop\Service\PaymentService());
        $eventManager->on(new \Shop\Service\EmailNotificationService());
        $eventManager->on(new \Shop\Service\OrderService());
        $eventManager->on(new \Shop\Service\OrderNotificationService());
        $eventManager->on(new \Shop\Sitemap\SitemapListener());
        /*
        */
        $eventManager->on($this);
    }

    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents()
    {
        return [
            //'Content.Model.PageTypes.get' => 'getContentPageTypes',
            'Settings.build' => 'buildSettings',
            'Backend.Menu.build.admin_primary' => ['callable' => 'buildBackendMenu', 'priority' => 5 ],
            'Backend.Menu.build.admin_system' => ['callable' => 'buildBackendSystemMenu' ],
        ];
    }

    /**
     * @param Event $event
     * @deprecated Defined in config file instead. see config/content.php
     */
    public function getContentPageTypes(Event $event)
    {
        $event->result['shop_category'] = [
            'title' => 'Shop Category',
            'className' => 'Shop.ShopCategory',
        ];
    }

    protected function _getMenuItems()
    {
        return [
            'orders' => [
                'title' => __d('shop', 'Orders'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
                'data-icon' => 'list',
            ],
            /*
            'order_invoices' => [
                'title' => 'Invoices',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrderInvoices', 'action' => 'index'],
                'data-icon' => 'eur'
            ],
            */
            'order_transactions' => [
                'title' => __d('shop', 'Transactions'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrderTransactions', 'action' => 'index'],
                'data-icon' => 'usd',
            ],
            'categories' => [
                'title' => __d('shop', 'Categories'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'index'],
                'data-icon' => 'folder',
            ],
            'products' => [
                'title' => __d('shop', 'Products'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
                'data-icon' => 'archive',
            ],
            'customers' => [
                'title' => __d('shop', 'Customers'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomers', 'action' => 'index'],
                'data-icon' => 'users',
            ],
            /*
            'customer_addresses' => [
                'title' => __d('shop', 'Customer Addresses'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomerAddresses', 'action' => 'index'],
                'data-icon' => 'address-book'
            ],
            */
            'customer_discounts' => [
                'title' => __d('shop', 'Customer Discounts'),
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomerDiscounts', 'action' => 'index'],
                'data-icon' => 'user-plus',
            ],
            /*
            'stocks' => [
                'title' => __d('shop', 'Stock'),
                'url' => ['plugin' => 'Shop', 'controller' => 'Stocks', 'action' => 'index'],
                'data-icon' => 'truck'
            ],
            'stock_transfers' => [
                'title' => __d('shop', 'Stock Transfers'),
                'url' => ['plugin' => 'Shop', 'controller' => 'StockTransfers', 'action' => 'index'],
                'data-icon' => 'truck'
            ],
            */
        ];
    }

    /**
     * @param Event $event
     */
    public function buildSettings(Event $event, SettingsManager $settings)
    {
        $settings->add('Shop', [

            // Owner
            'Owner.name' => [
                'type' => 'string',
            ],
            'Owner.street1' => [
                'type' => 'string',
            ],
            'Owner.street2' => [
                'type' => 'string',
            ],
            'Owner.zipcode' => [
                'type' => 'string',
            ],
            'Owner.city' => [
                'type' => 'string',
            ],
            'Owner.country' => [
                'type' => 'string',
            ],
            'Owner.taxId' => [
                'type' => 'string',
            ],

            // Pages
            'Pages.termsUrl' => [
                'type' => 'string',
            ],

            // Demo
            'Demo.enabled' => [
                'type' => 'boolean',
            ],
            'Demo.username' => [
                'type' => 'string',
            ],

            // Cart
            'Cart.requireAuth' => [
                'type' => 'boolean',
            ],

            // Order
            'Order.nrPrefix' => [
                'type' => 'string',
            ],
            'Order.nrSuffix' => [
                'type' => 'string',
            ],

            // Invoice
            'Invoice.nrPrefix' => [
                'type' => 'string',
            ],
            'Invoice.nrSuffix' => [
                'type' => 'string',
            ],

            // Price
            'Price.baseCurrency' => [
                'type' => 'string',
            ],
            'Price.requireAuth' => [
                'type' => 'boolean',
            ],
            'Price.displayNet' => [
                'type' => 'boolean',
            ],

            // Layout
            'Layout.default' => [
                'type' => 'string',
            ],
            'Layout.checkout' => [
                'type' => 'string',
            ],
            'Layout.payment' => [
                'type' => 'string',
            ],
            'Layout.order' => [
                'type' => 'string',
            ],

            // Catalogue
            'Catalogue.index_category_id' => [
                'type' => 'string',
            ],

            // Routing
            'Router.enablePrettyUrls' => [
                'type' => 'boolean',
            ],
            'Router.forceCanonical' => [
                'type' => 'boolean',
            ],

        ]);
    }

    /**
     * Build backend routes
     */
    public function backendRoutes(RouteBuilder $routes)
    {
        //$routes->addExtensions(['pdf']);
        $routes->connect('/', ['controller' => 'Shop', 'action' => 'index'], ['_name' => 'index']);
        //$routes->connect('/', ['controller' => 'ShopOrders', 'action' => 'index']);
        $routes->fallbacks('DashedRoute');
    }

    /**
     * @param Event $event
     */
    public function buildBackendMenu(Event $event, \Banana\Menu\Menu $menu)
    {
        $menu->addItem([
            'title' => __d('shop', 'Shop'),
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
            'data-icon' => 'shopping-cart',
            'children' => $this->_getMenuItems(),
        ]);
    }

    public function buildBackendSystemMenu(Event $event, \Banana\Menu\Menu $menu)
    {
        $menu->addItem([
            'title' => __d('shop', 'Shop Countries'),
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopCountries', 'action' => 'index'],
            'data-icon' => 'flag-checkered',
        ]);
    }
}
