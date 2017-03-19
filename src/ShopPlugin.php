<?php

namespace Shop;


use Banana\Plugin\PluginInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Shop\Sitemap\ShopCategoriesSitemapProvider;
use Shop\Sitemap\ShopProductsSitemapProvider;

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
            'Backend.Menu.get' => 'getBackendMenu',
        ];
    }

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
                'addresses' => [
                    'title' => 'Addresses',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopAddresses', 'action' => 'index'],
                    'data-icon' => 'home'
                ],
                'tags' => [
                    'title' => 'Tags',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopTags', 'action' => 'index'],
                    'data-icon' => 'tags'
                ],
                'texts' => [
                    'title' => 'Texts',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopTexts', 'action' => 'index'],
                    'data-icon' => 'book'
                ],
                'customers' => [
                    'title' => 'Customers',
                    'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomers', 'action' => 'index'],
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
     * @param EventManager $eventManager
     * @return $this
     */
    public function registerEvents(EventManager $eventManager)
    {
        //$eventManager->on(new ShopCategoriesSitemapProvider());
        //$eventManager->on(new ShopProductsSitemapProvider());
    }

    /**
     * @param array $config
     * @return void
     */
    public function __invoke(array $config = [])
    {
        // TODO: Implement __invoke() method.
    }
}