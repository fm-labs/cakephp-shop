<?php
return [
    'Backend.Plugin.Shop.Menu' => [

        'app' => [

            [
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
                ]
            ],
        ]
    ]
];