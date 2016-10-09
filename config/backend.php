<?php
return [
    'Backend.Plugin.Shop.Menu' => [

        'title' => 'Shop',
        'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
        'data-icon' => 'shopping-cart',

        '_children' => [
            'Orders' => [
                'title' => 'Orders',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopOrders', 'action' => 'index'],
                'data-icon' => 'eur'
            ],
            'Categories' => [
                'title' => 'Categories',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'index'],
                'data-icon' => 'folder'
            ],
            'Products' => [
                'title' => 'Products',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'index'],
                'data-icon' => 'gift'
            ],
            'Addresses' => [
                'title' => 'Addresses',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopAddresses', 'action' => 'index'],
                'data-icon' => 'home'
            ],
            'Tags' => [
                'title' => 'Tags',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopTags', 'action' => 'index'],
                'data-icon' => 'tags'
            ],
            'Texts' => [
                'title' => 'Texts',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopTexts', 'action' => 'index'],
                'data-icon' => 'book'
            ],
            'Customers' => [
                'title' => 'Customers',
                'url' => ['plugin' => 'Shop', 'controller' => 'ShopCustomers', 'action' => 'index'],
                'data-icon' => 'users'
            ],
            'Stocks' => [
                'title' => 'Stock',
                'url' => ['plugin' => 'Shop', 'controller' => 'Stocks', 'action' => 'index'],
                'data-icon' => 'truck'
            ],
            'StockTransfers' => [
                'title' => 'StockTransfers',
                'url' => ['plugin' => 'Shop', 'controller' => 'StockTransfers', 'action' => 'index'],
                'data-icon' => 'truck'
            ],
        ]
    ]
];