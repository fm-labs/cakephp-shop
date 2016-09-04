<?php
return [
    'Shop' => [

        'defaultLocale' => 'de',
        'locales' => [
            'de' => 'Deutsch',
            'en' => 'English',
        ],

        'Router' => [
            'enablePrettyUrls' => true,
            'forceCanonical' => true,
        ],

        'HtmlEditor' => [
            'default' => [
                'convert_urls' => false,
                '_image_list' => ['plugin' => 'Banana', 'controller' => 'HtmlEditor', 'action' => 'imageList', 'shop'],
                '_link_list' => ['plugin' => 'Banana', 'controller' => 'HtmlEditor', 'action' => 'linkList']
            ]
        ],

        'ShippingMethods' => [
        ],

        'PaymentMethods' => [
        ],

        'Catalogue' => [
            'index_category_id' => null
        ],

        'Email' => [

        ]


    ]
];