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
    $routes->connect('/cart/:action',
        ['controller' => 'Cart']
    );
    $routes->connect('/cart/:action/*',
        ['controller' => 'Cart']
    );

    $routes->connect('/payment/:action/*',
        ['controller' => 'Payment'],
        []
    );
    $routes->connect('/payment/:action',
        ['controller' => 'Payment'],
        []
    );
    $routes->connect('/payment',
        ['controller' => 'Payment'],
        ['pass' => []]
    );

    $routes->connect('/orders/:action/*',
        ['controller' => 'Orders'],
        []
    );
    $routes->connect('/orders/:action',
        ['controller' => 'Orders'],
        []
    );
    $routes->connect('/orders',
        ['controller' => 'Orders'],
        ['pass' => []]
    );

    $routes->connect('/checkout/:action/:cartid/*',
        ['controller' => 'Checkout'],
        ['pass' => ['cartid']]
    );
    $routes->connect('/checkout/:action/:cartid',
        ['controller' => 'Checkout'],
        ['pass' => ['cartid']]
    );
    $routes->connect('/checkout/:action',
        ['controller' => 'Checkout'],
        ['pass' => []]
    );
    $routes->connect('/checkout',
        ['controller' => 'Checkout', 'action' => 'index'],
        ['pass' => []]
    );

    $routes->connect('/customer',
        ['controller' => 'Customer', 'action' => 'index'],
        ['_name' => 'customer']
    );

    if (Cake\Core\Configure::read('Shop.Router.enablePrettyUrls') && false == true):

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


    //$routes->connect('/:controller/:action');
    //$routes->connect('/:controller');
    $routes->fallbacks('DashedRoute');
});
