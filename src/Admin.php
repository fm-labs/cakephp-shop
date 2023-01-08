<?php
declare(strict_types=1);

namespace Shop;

use Admin\Core\BaseAdminPlugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

class Admin extends BaseAdminPlugin implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function routes(RouteBuilder $routes): void
    {
        //$routes->addExtensions(['pdf']);
        $routes->connect('/', ['controller' => 'Shop', 'action' => 'index'], ['_name' => 'index']);
        $routes->fallbacks(DashedRoute::class);
    }

    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Menu.build.admin_primary' => ['callable' => 'buildAdminMenu', 'priority' => 10 ],
            'Admin.Menu.build.admin_system' => ['callable' => 'buildAdminSystemMenu', 'priority' => 10 ],
            'Admin.Menu.build.admin_developer' => ['callable' => 'buildAdminDevMenu', 'priority' => 10 ],
        ];
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cupcake\Menu\MenuItemCollection $menu
     */
    public function buildAdminMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
        $menu->addItem([
            'title' => __d('shop', 'Shop'),
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
            'data-icon' => 'shopping-cart',
            'children' => [
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
                'shop_countries' => [
                    'title' => __d('shop', 'Shop Countries'),
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCountries', 'action' => 'index'],
                    'data-icon' => 'flag-checkered',
                ]
            ],
        ]);
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cupcake\Menu\MenuItemCollection $menu
     */
    public function buildAdminSystemMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cupcake\Menu\MenuItemCollection $menu
     */
    public function buildAdminDevMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
    }
}
