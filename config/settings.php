<?php
return ['Settings' => [
    'Shop' => [
        'groups' => [
            'Shop.Owner' => [
                'label' => __('Shop Owner'),
            ],
            'Shop.Content' => [
                'label' => __('Shop Content'),
            ],
            'Shop.Cart' => [
                'label' => __('Shop Cart'),
            ],
            'Shop.Order' => [
                'label' => __('Shop Order'),
            ],
            'Shop.Invoice' => [
                'label' => __('Shop Invoice'),
            ],
            'Shop.Design' => [
                'label' => __('Shop Design'),
            ],
            'Shop.Catalogue' => [
                'label' => __('Shop Catalogue'),
            ],
            'Shop.System' => [
                'label' => __('Shop System'),
            ],
            'Shop.Demo' => [
                'label' => __('Shop Demo'),
            ],
        ],
        'schema' => [
            // Owner
            'Shop.Owner.name' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
                'required' => true,
            ],
            'Shop.Owner.street1' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
                'required' => true,
            ],
            'Shop.Owner.street2' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
            ],
            'Shop.Owner.zipcode' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
                'required' => true,
            ],
            'Shop.Owner.city' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
                'required' => true,
            ],
            'Shop.Owner.country' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
                'required' => true,
            ],
            'Shop.Owner.taxId' => [
                'group' => 'Shop.Owner',
                'type' => 'string',
            ],

            // Pages
            'Shop.Pages.termsUrl' => [
                'group' => 'Shop.Content',
                'type' => 'string',
                'default' => '/shop/pages/terms',
            ],

            // Demo
            'Shop.Demo.enabled' => [
                'group' => 'Shop.Demo',
                'type' => 'boolean',
                'default' => '0',
            ],
            'Shop.Demo.username' => [
                'group' => 'Shop.Demo',
                'type' => 'string',
                'default' => 'demo',
            ],

            // Cart
            'Shop.Cart.requireAuth' => [
                'group' => 'Shop.Cart',
                'type' => 'boolean',
                'default' => '0',
            ],

            // Order
            'Shop.Order.nrPrefix' => [
                'group' => 'Shop.Order',
                'type' => 'string',
                'default' => 'BE',
            ],
            'Shop.Order.nrSuffix' => [
                'group' => 'Shop.Order',
                'type' => 'string',
                'default' => '',
            ],

            // Invoice
            'Shop.Invoice.nrPrefix' => [
                'group' => 'Shop.Invoice',
                'type' => 'string',
                'default' => 'RE',
            ],
            'Shop.Invoice.nrSuffix' => [
                'group' => 'Shop.Invoice',
                'type' => 'string',
                'default' => '',
            ],

            // Price
            'Shop.Price.baseCurrency' => [
                'group' => 'Shop.Order',
                'type' => 'string',
                'default' => 'EUR',
                'input' => [
                    'type' => 'select',
                    'options' => [
                        'EUR' => 'Euro',
                        'USD' => 'US Dollar',
                    ],
                ],
                'required' => true,
            ],
            'Shop.Price.requireAuth' => [
                'group' => 'Shop.Order',
                'type' => 'boolean',
                'default' => '0',
            ],
            'Shop.Price.displayNet' => [
                'group' => 'Shop.Order',
                'type' => 'boolean',
                'default' => '0',
            ],

            // Layout
            'Shop.Layout.default' => [
                'group' => 'Shop.Design',
                'type' => 'string',
            ],
            'Shop.Layout.checkout' => [
                'group' => 'Shop.Design',
                'type' => 'string',
            ],
            'Shop.Layout.payment' => [
                'group' => 'Shop.Design',
                'type' => 'string',
            ],
            'Shop.Layout.order' => [
                'group' => 'Shop.Design',
                'type' => 'string',
            ],

            // Catalogue
            'Shop.Catalogue.index_category_id' => [
                'group' => 'Shop.Catalogue',
                'type' => 'integer',
                'input' => [
                    'empty' => __('Select shop category'),
                    'options' => function () {
                        $Categories = \Cake\ORM\TableRegistry::getTableLocator()->get('Shop.ShopCategories');

                        return $Categories->find('list')->toArray();
                    },
                ],
            ],

            // Routing
            'Shop.Router.enablePrettyUrls' => [
                'group' => 'Shop.Routing',
                'type' => 'boolean',
                'default' => '0',
            ],
            'Shop.Router.forceCanonical' => [
                'group' => 'Shop.Routing',
                'type' => 'boolean',
                'default' => '0',
            ],

        ],
    ],
]];
