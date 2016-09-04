<?php

namespace Shop\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Media\Lib\Media\MediaManager;
use App\Controller\Admin\AppController as BaseAdminAppController;

class AppController extends BaseAdminAppController
{
    public $locale;

    public $paginate = [
        'limit' => 100,
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $locale = $this->request->query('locale');
        $this->locale = ($locale) ? $locale : Configure::read('Shop.defaultLocale');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('locale', $this->locale);
    }

    protected function _getGalleryList()
    {
        $list = [];
        $mm = MediaManager::get('shop');
        $list = $mm->getSelectListRecursive();
        return $list;
    }

    public static function backendMenu()
    {
        return [
            'plugin.shop' => [
                'plugin' => 'Shop',
                'title' => 'Shop',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
                'icon' => 'shopping-cart',

                '_children' => [
                    'Orders' => [
                        'title' => 'Orders',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
                        'icon' => 'eur'
                    ],
                    'Categories' => [
                        'title' => 'Categories',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'index'],
                        'icon' => 'folder'
                    ],
                    'Products' => [
                        'title' => 'Products',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
                        'icon' => 'gift'
                    ],
                    'Addresses' => [
                        'title' => 'Addresses',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopAddresses', 'action' => 'index'],
                        'icon' => 'home'
                    ],
                    'Tags' => [
                        'title' => 'Tags',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopTags', 'action' => 'index'],
                        'icon' => 'tags'
                    ],
                    'Texts' => [
                        'title' => 'Texts',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopTexts', 'action' => 'index'],
                        'icon' => 'book'
                    ],
                    'Customers' => [
                        'title' => 'Customers',
                        'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomers', 'action' => 'index'],
                        'icon' => 'users'
                    ],
                    'Stocks' => [
                        'title' => 'Stock',
                        'url' => ['plugin' => 'Shop', 'controller' => 'Stocks', 'action' => 'index'],
                        'icon' => 'truck'
                    ],
                    'StockTransfers' => [
                        'title' => 'StockTransfers',
                        'url' => ['plugin' => 'Shop', 'controller' => 'StockTransfers', 'action' => 'index'],
                        'icon' => 'truck'
                    ],
                ]
            ]
        ];
    }

}
