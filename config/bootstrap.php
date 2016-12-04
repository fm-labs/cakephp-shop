<?php
use Cake\Core\Plugin;
use Backend\Lib\Backend;
use Content\Lib\ContentManager;

if (Plugin::loaded('Backend')) {
    Backend::hookPlugin('Shop');
}


if (Plugin::loaded('Content')) {
    ContentManager::register('PostType', [
        'shop_category' => 'Shop\Post\ShopCategoryPostHandler'
    ]);
    ContentManager::register('MenuItemType', [
        'shop_category' => 'Shop\Menu\ShopCategoryMenuHandler',
    ]);

    // @deprecated
    ContentManager::register('PageType', [
        'shop_category' => [
            'name' => 'Shop Category',
            'class' => 'Shop\Page\ShopCategoryPageType'
        ]
    ]);

    ContentManager::register('ContentModule', [
        'RandomCategoryProduct' => [
            'class' => 'Shop.RandomCategoryProduct'
        ]
    ]);
}