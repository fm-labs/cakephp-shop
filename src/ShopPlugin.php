<?php

namespace Shop;

use Banana\Plugin\PluginInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * Class ShopPlugin
 *
 * @package Shop
 */
class ShopPlugin implements PluginInterface, EventListenerInterface
{

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
            'Content.Model.PageTypes.get' => 'getContentPageTypes',
            'Settings.get' => 'getSettings',
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 5 ],
            'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    /**
     * @param Event $event
     */
    public function getContentPageTypes(Event $event)
    {
        $event->result['shop_category'] = [
            'title' => 'Shop Category',
            'className' => 'Shop.ShopCategory'
        ];
    }

    /**
     * @param Event $event
     */
    public function getSettings(Event $event)
    {
        $event->result['Shop'] = [
            'Router.enablePrettyUrls' => [
                'type' => 'boolean',
            ],
            'Router.forceCanonical' => [
                'type' => 'boolean',
            ],
        ];
    }

    /**
     * Build backend routes
     */
    public function buildBackendRoutes()
    {
        Router::scope('/shop/admin', ['plugin' => 'Shop', 'prefix' => 'admin', '_namePrefix' => 'shop:admin:'], function ($routes) {
            //$routes->addExtensions(['pdf']);
            $routes->connect(
                '/',
                ['controller' => 'Shop', 'action' => 'index'],
                ['_name' => 'index']
            );
            $routes->fallbacks('DashedRoute');
        });
    }

    /**
     * @param Event $event
     */
    public function getBackendMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'Shop',
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
            'data-icon' => 'shopping-cart',

            'children' => [
                'orders' => [
                    'title' => 'Orders',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
                    'data-icon' => 'list'
                ],
                /*
                'order_invoices' => [
                    'title' => 'Invoices',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrderInvoices', 'action' => 'index'],
                    'data-icon' => 'eur'
                ],
                */
                'order_transactions' => [
                    'title' => 'Order Transactions',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrderTransactions', 'action' => 'index'],
                    'data-icon' => 'eur'
                ],
                'categories' => [
                    'title' => 'Categories',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'index'],
                    'data-icon' => 'folder'
                ],
                'products' => [
                    'title' => 'Products',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
                    'data-icon' => 'gift'
                ],
                'customers' => [
                    'title' => 'Customers',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomers', 'action' => 'index'],
                    'data-icon' => 'users'
                ],
                'customer_addresses' => [
                    'title' => 'Customer Addresses',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomerAddresses', 'action' => 'index'],
                    'data-icon' => 'users'
                ],
                'customer_discounts' => [
                    'title' => 'Customer Discounts',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomerDiscounts', 'action' => 'index'],
                    'data-icon' => 'users'
                ],
                /*
                'stocks' => [
                    'title' => 'Stock',
                    'url' => ['plugin' => 'Shop', 'controller' => 'Stocks', 'action' => 'index'],
                    'data-icon' => 'truck'
                ],
                'stock_transfers' => [
                    'title' => 'StockTransfers',
                    'url' => ['plugin' => 'Shop', 'controller' => 'StockTransfers', 'action' => 'index'],
                    'data-icon' => 'truck'
                ],
                */
                'countries' => [
                    'title' => 'Countries',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCountries', 'action' => 'index'],
                    'data-icon' => ''
                ],
            ]
        ]);
    }

    /**
     * @param array $config
     * @return void
     */
    public function __invoke(array $config = [])
    {
        EventManager::instance()->on(new \Shop\Event\CartListener());
        EventManager::instance()->on(new \Shop\Event\CustomerListener());
        EventManager::instance()->on(new \Shop\Event\PaymentListener());
        EventManager::instance()->on(new \Shop\Event\EmailNotificationListener());
        EventManager::instance()->on(new \Shop\Sitemap\SitemapListener());
    }
}
