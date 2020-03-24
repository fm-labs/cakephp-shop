<?php
return ['Settings' => [
    'Shop' => [
        'settings' => [
            // Owner
            'Shop.Owner.name' => [
                'type' => 'string',
            ],
            'Shop.Owner.street1' => [
                'type' => 'string',
            ],
            'Shop.Owner.street2' => [
                'type' => 'string',
            ],
            'Shop.Owner.zipcode' => [
                'type' => 'string',
            ],
            'Shop.Owner.city' => [
                'type' => 'string',
            ],
            'Shop.Owner.country' => [
                'type' => 'string',
            ],
            'Shop.Owner.taxId' => [
                'type' => 'string',
            ],

            // Pages
            'Shop.Pages.termsUrl' => [
                'type' => 'string',
            ],

            // Demo
            'Shop.Demo.enabled' => [
                'type' => 'boolean',
            ],
            'Shop.Demo.username' => [
                'type' => 'string',
            ],

            // Cart
            'Shop.Cart.requireAuth' => [
                'type' => 'boolean',
            ],

            // Order
            'Shop.Order.nrPrefix' => [
                'type' => 'string',
            ],
            'Shop.Order.nrSuffix' => [
                'type' => 'string',
            ],

            // Invoice
            'Shop.Invoice.nrPrefix' => [
                'type' => 'string',
            ],
            'Shop.Invoice.nrSuffix' => [
                'type' => 'string',
            ],

            // Price
            'Shop.Price.baseCurrency' => [
                'type' => 'string',
                'default' => 'EUR',
                'input' => [
                    'type' => 'select',
                    'options' => [
                        'EUR' => 'Euro',
                        'USD' => 'US Dollar'
                    ]
                ]
            ],
            'Shop.Price.requireAuth' => [
                'type' => 'boolean',
            ],
            'Shop.Price.displayNet' => [
                'type' => 'boolean',
            ],

            // Layout
            'Shop.Layout.default' => [
                'type' => 'string',
            ],
            'Shop.Layout.checkout' => [
                'type' => 'string',
            ],
            'Shop.Layout.payment' => [
                'type' => 'string',
            ],
            'Shop.Layout.order' => [
                'type' => 'string',
            ],

            // Catalogue
            'Shop.Catalogue.index_category_id' => [
                'type' => 'integer',
                'input' => [
                    'empty' => __('Select shop category'),
                    'options' => function () {
                        $Categories = \Cake\ORM\TableRegistry::getTableLocator()->get('Shop.ShopCategories');
                        return $Categories->find('list')->toArray();
                    }
                ]
            ],

            // Routing
            'Shop.Router.enablePrettyUrls' => [
                'type' => 'boolean',
            ],
            'Shop.Router.forceCanonical' => [
                'type' => 'boolean',
            ],

        ]
    ]
]];
