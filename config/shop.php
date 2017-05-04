<?php
return [
    'Shop' => [

        'defaultLocale' => 'de',
        'locales' => [
            'de' => 'Deutsch',
            'en' => 'English',
        ],

        'Layout' => [
            'default' => null,
            'checkout' => null,
            'payment' => null,
            'order' => null,
        ],

        'Router' => [
            'enablePrettyUrls' => true,
            'forceCanonical' => true,
        ],

        'HtmlEditor' => [
            'default' => [
                'convert_urls' => false,
                '_image_list' => ['plugin' => 'Content', 'controller' => 'HtmlEditor', 'action' => 'imageList', 'shop'],
                '_link_list' => ['plugin' => 'Content', 'controller' => 'HtmlEditor', 'action' => 'linkList']
            ]
        ],

        'Checkout' => [
            'Steps' => [
                'customer' => [
                    'className' => 'Shop.Customer'
                ],
                'shipping_address' => [
                    'className' => 'Shop.ShippingAddress'
                ],
                'shipping' => [
                    'className' => 'Shop.Shipping'
                ],
                'billing_address' => [
                    'className' => 'Shop.BillingAddress'
                ],
                'payment' => [
                    'className' => 'Shop.Payment'
                ],
                'review' => [
                    'className' => 'Shop.Review'
                ],
            ]
        ],

        'Shipping' => [
            'Engines' => [
                /*
                'custom' => [
                    'engine' => 'Shop.CustomRate',
                    'enabled' => true,
                    'name' => 'Standard Versand',
                ],
                'fixed' => [
                    'engine' => 'Shop.FixedRate',
                    'enabled' => true,
                    'name' => 'Standard Versand Express',
                    'cost' => 10.00
                ],
                'email' => [
                    'engine' => 'Shop.Email',
                    'enabled' => true,
                    //'emailConfig' => 'shop_email_shipping'
                ]
                */
            ]
        ],

        'Payment' => [
            'Engines' => [
                /*
                'credit_card_internal' => [
                    'engine' => 'Shop.CreditCardInternal',
                    'enabled' => false,
                ],
                'mpay24' => [
                    'engine' => 'Shop.Mpay24',
                    'enabled' => false,
                ],
                'payment_slip' => [
                    'engine' => 'Shop.PaymentSlip',
                    'enabled' => false,
                ],
                */
            ]
        ],

        'Order' => [
            'nrPrefix' => 'BE',
            'nrStart' => 1,
            'nrGroup' => date('Y'),
            'nrSuffix' => '',
            'nrZerofill' => 5
        ],

        'Catalogue' => [
            'index_category_id' => null
        ],

        'Email' => [

        ]


    ]
];