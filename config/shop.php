<?php
return [
    'Shop' => [

        'defaultLocale' => 'de',
        'locales' => [
            'de' => 'Deutsch',
            'en' => 'English',
        ],

        'Owner' => [
            'name'          => '',
            'street1'       => '',
            'street2'       => '',
            'zipcode'       => '',
            'city'          => '',
            'country'       => '',
            'taxId'         => '',
        ],

        'Pages' => [
            'termsUrl' => '/content/terms',
        ],

        'Layout' => [
            'default' => null,
            'checkout' => null,
            'payment' => null,
            'order' => null,
        ],

        'Router' => [
            'enablePrettyUrls' => false,
            'forceCanonical' => false,
        ],

        'Address' => [
            'useCompanyName' => false,
            'useTaxId' => false,
        ],

        'Checkout' => [
            'Steps' => [
                /*
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
                    'className' => 'Shop.Submit'
                ],
                */
            ],
        ],

        'Shipping' => [
            'Engines' => [

            ],
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
            ],
        ],

        'Order' => [
            'nrPrefix' => 'BE',
            'nrStart' => 1,
            'nrGroup' => date('Y'),
            'nrSuffix' => '',
            'nrZerofill' => 5,
        ],

        'Invoice' => [
            'nrPrefix' => 'RE',
            'nrStart' => 1,
            'nrGroup' => date('Y'),
            'nrSuffix' => '',
            'nrZerofill' => 5,
        ],

        'Catalogue' => [
            'index_category_id' => null,
        ],

        'Pdf' => [
            'engine' => '\\Tcpdf\\Lib\\CakeTcpdf',
        ],

        'Mailer' => [
            'customerProfile' => 'shop_customer',
            'merchantProfile' => 'shop_merchant',
        ]
    ],
];
