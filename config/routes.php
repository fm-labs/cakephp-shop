<?php
use Cake\Routing\Router;

// Shop frontend routes
Router::scope('/shop', ['plugin' => 'Shop', '_namePrefix' => 'shop:'], function ($routes) {

    $routes->routeClass('Cake\Routing\Route\DashedRoute');

    $routes->connect('/',
        ['plugin' => 'Shop', 'controller' => 'Categories', 'action' => 'index'],
        ['_name' => 'index']
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

    $routes->connect('/checkout/debug',
        ['controller' => 'Checkout', 'action' => 'debug'],
        ['_name' => 'debug_checkout']
    );

    if (Cake\Core\Configure::read('Shop.Router.enablePrettyUrls')):

        // shop product routes
        //@TODO add product_id regex pattern
        //@TODO add product regex pattern
        $routes->connect('/:category/:product/product/:product_id',
            ['plugin' => 'Shop', 'controller' => 'Products', 'action' => 'view'],
            ['pass' => ['product_id'], 'category' => '[\w\/\-\_]+']
        );

        //@TODO add product regex pattern
        //@TODO add product regex pattern
        $routes->connect('/:category/:product/product',
            ['plugin' => 'Shop', 'controller' => 'Products', 'action' => 'view'],
            ['pass' => [], 'category' => '[\w\/\-\_]+']
        );

        //@TODO add product_id regex pattern
        //@TODO add product regex pattern
        $routes->connect('/:product/product/:product_id',
            ['plugin' => 'Shop', 'controller' => 'Products', 'action' => 'view'],
            ['pass' => ['product_id']]
        );

        //@TODO add product_id regex pattern
        $routes->connect('/product/:product_id',
            ['plugin' => 'Shop', 'controller' => 'Products', 'action' => 'view'],
            ['pass' => ['product_id']]
        );

        // shop category routes
        $routes->connect('/:category/:category_id',
            ['plugin' => 'Shop', 'controller' => 'Categories', 'action' => 'view'],
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
