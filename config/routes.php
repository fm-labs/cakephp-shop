<?php
use Cake\Routing\Router;

// Shop frontend routes
Router::scope('/shop', ['plugin' => 'Shop', '_namePrefix' => 'shop:'], function ($routes) {

    $routes->routeClass('Cake\Routing\Route\DashedRoute');

    $routes->connect('/',
        ['controller' => 'Catalogue', 'action' => 'index'],
        ['_name' => 'index']
    );
    $routes->connect('/catalogue/:action/*',
        ['controller' => 'Catalogue']
    );

    $routes->connect('/cart',
        ['controller' => 'Cart', 'action' => 'index'],
        ['_name' => 'cart']);
    $routes->connect('/cart/:action/*',
        ['controller' => 'Cart']
    );

    $routes->connect('/checkout/:step/*',
        ['controller' => 'Checkout', 'action' => 'step'],
        ['pass' => ['step']]
    );
    $routes->connect('/checkout/:step',
        ['controller' => 'Checkout', 'action' => 'step'],
        ['pass' => ['step']]
    );
    $routes->connect('/checkout',
        ['controller' => 'Checkout', 'action' => 'index'],
        ['_name' => 'checkout']
    );


    if (Cake\Core\Configure::read('Shop.Router.enablePrettyUrls')):

        // shop product routes
        $routes->connect('/:category/:product/product/:product_id',
            ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'view'],
            ['pass' => ['product_id'], 'category' => '[\w\/\-\_]+']
        );

        $routes->connect('/:category/:product/product',
            ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'view'],
            ['pass' => ['product_id'], 'category' => '[\w\/\-\_]+']
        );

        $routes->connect('/:product/product/:product_id',
            ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'view'],
            ['pass' => ['product_id']]
        );

        $routes->connect('/product/:product_id',
            ['plugin' => 'Shop', 'controller' => 'ShopProducts', 'action' => 'view'],
            ['pass' => ['product_id']]
        );

        // shop category routes
        $routes->connect('/:category/:category_id',
            ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'view'],
            ['pass' => ['category_id'], 'category' => '[\w\/\-\_]+']
        );

        /*
        $routes->connect('/:category',
            ['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'view'],
            ['pass' => ['category_id'], 'category' => '[\w\/\-\_]+']
        );
        */

    endif;


    $routes->connect('/:controller');
    $routes->fallbacks('DashedRoute');
});

// Shop admin routes
Router::scope('/shop/admin', ['plugin' => 'Shop', 'prefix' => 'admin', '_namePrefix' => 'shop:admin:'], function ($routes) {

    $routes->connect('/',
        ['controller' => 'Shop', 'action' => 'index'],
        ['_name' => 'index']
    );
    //$routes->connect('/:controller');

    $routes->fallbacks('DashedRoute');
});
