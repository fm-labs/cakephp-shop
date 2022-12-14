<?php
declare(strict_types=1);

namespace Shop;

use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\RouteBuilder;
use Cupcake\Model\EntityTypeRegistry;
use Cupcake\Plugin\BasePlugin;

/**
 * Class ShopPlugin
 *
 * @package Shop
 */
class Plugin extends BasePlugin implements EventListenerInterface
{
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        $app->addPlugin('Content');
        $app->addPlugin('Media');

        $eventManager = EventManager::instance();
        $eventManager->on(new \Shop\Service\CartService());
        $eventManager->on(new \Shop\Service\CustomerService());
        $eventManager->on(new \Shop\Service\EmailNotificationService());
        $eventManager->on(new \Shop\Service\OrderService());
        $eventManager->on(new \Shop\Service\OrderNotificationService());
        $eventManager->on(new \Shop\Service\PaymentService());
        $eventManager->on(new \Shop\Service\ShopRulesService());
        $eventManager->on(new \Shop\Sitemap\SitemapListener());
        $eventManager->on($this);

        EntityTypeRegistry::register('Content.Menu', 'shop_category', [
            'label' => __('Shop Category'),
            'className' => '\\Content\\Model\\Entity\\Menu\\ShopCategoryMenuType',
        ]);
    }

    public function getConfigurationUrl()
    {
        return \Cake\Core\Plugin::isLoaded('Settings')
            ? ['_name' => 'settings:manage', $this->getName()]
            : null;
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
            'Admin.Menu.build.admin_primary' => ['callable' => 'buildAdminMenu', 'priority' => 5 ],
            'Admin.Menu.build.admin_system' => ['callable' => 'buildAdminSystemMenu' ],
        ];
    }

    /**
     * Build admin routes
     *
     * @param \Cake\Routing\RouteBuilder $routes
     */
    public function adminRoutes(RouteBuilder $routes)
    {
        //$routes->addExtensions(['pdf']);
        $routes->connect('/', ['controller' => 'Shop', 'action' => 'index'], ['_name' => 'index']);
        //$routes->connect('/', ['controller' => 'ShopOrders', 'action' => 'index']);
        $routes->fallbacks('DashedRoute');
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
            ],
        ]);
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cupcake\Menu\MenuItemCollection $menu
     */
    public function buildAdminSystemMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
        $menu->addItem([
            'title' => __d('shop', 'Shop Countries'),
            'url' => ['plugin' => 'Shop', 'controller' => 'ShopCountries', 'action' => 'index'],
            'data-icon' => 'flag-checkered',
        ]);
    }
}
