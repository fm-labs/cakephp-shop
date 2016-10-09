<?php
use Cake\Core\Plugin;

if (Plugin::loaded('Backend')) {
    \Backend\Lib\Backend::hookPlugin('Shop');
}


if (Plugin::loaded('Content')) {
    \Content\Lib\ContentManager::register('PageType', [
        'shop_category' => [
            'name' => 'Shop Category',
            'class' => 'Shop\Page\ShopCategoryPageType'
        ]
    ]);

    \Content\Lib\ContentManager::register('ContentModule', [
        'RandomCategoryProduct' => [
            'class' => 'Shop.RandomCategoryProduct'
        ]
    ]);
}