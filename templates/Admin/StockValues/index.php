<?php $this->Breadcrumbs->add(__d('shop','Stock Values')); ?>

<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Stock Value')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Stocks')),
    ['controller' => 'ShopStocks', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Stock')),
    ['controller' => 'ShopStocks', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="stockValues index">

    <?php $fields = [
    'id','shop_stock_id','shop_product_id','value','last_transfer_in','last_transfer_out','created','modified',    ] ?>
    <?= $this->cell('Admin.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.StockValues',
        'data' => $stockValues,
        'fields' => $fields,
        'debug' => true,
        'rowActions' => [
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

</div>

<pre>
    /tmp/bake/index-ctp.php (line 60)
########## DEBUG ##########
[
	'dataForView' => [
		'modelObject' => object(Shop\Model\Table\StockValuesTable) {

			'registryAlias' => 'Shop.StockValues',
			'table' => 'shop_stock_values',
			'alias' => 'StockValues',
			'entityClass' => 'Shop\Model\Entity\StockValue',
			'associations' => [
				(int) 0 => 'shopstocks',
				(int) 1 => 'shopproducts'
			],
			'behaviors' => [
				(int) 0 => 'Timestamp'
			],
			'defaultConnection' => 'default',
			'connectionName' => 'default'
		
		},
		'modelClass' => 'Shop.StockValues',
		'schema' => object(Cake\Database\Schema\TableSchema) {
			[protected] _table => 'shop_stock_values'
			[protected] _columns => [
				'id' => [
					'type' => 'integer',
					'length' => (int) 11,
					'unsigned' => true,
					'null' => false,
					'default' => null,
					'comment' => '',
					'autoIncrement' => true,
					'baseType' => null,
					'precision' => null
				],
				'shop_stock_id' => [
					'type' => 'integer',
					'length' => (int) 11,
					'unsigned' => true,
					'null' => false,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'autoIncrement' => null
				],
				'shop_product_id' => [
					'type' => 'integer',
					'length' => (int) 11,
					'unsigned' => true,
					'null' => false,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'autoIncrement' => null
				],
				'value' => [
					'type' => 'integer',
					'length' => (int) 11,
					'unsigned' => false,
					'null' => false,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'autoIncrement' => null
				],
				'last_transfer_in' => [
					'type' => 'datetime',
					'length' => null,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null
				],
				'last_transfer_out' => [
					'type' => 'datetime',
					'length' => null,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null
				],
				'created' => [
					'type' => 'datetime',
					'length' => null,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null
				],
				'modified' => [
					'type' => 'datetime',
					'length' => null,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null
				]
			]
			[protected] _typeMap => [
				'id' => 'integer',
				'shop_stock_id' => 'integer',
				'shop_product_id' => 'integer',
				'value' => 'integer',
				'last_transfer_in' => 'datetime',
				'last_transfer_out' => 'datetime',
				'created' => 'datetime',
				'modified' => 'datetime'
			]
			[protected] _indexes => []
			[protected] _constraints => [
				'primary' => [
					'type' => 'primary',
					'columns' => [
						(int) 0 => 'id'
					],
					'length' => []
				],
				'shop_product_id_UNIQUE' => [
					'type' => 'unique',
					'columns' => [
						(int) 0 => 'shop_product_id'
					],
					'length' => []
				]
			]
			[protected] _options => [
				'engine' => 'InnoDB',
				'collation' => 'utf8_general_ci'
			]
			[protected] _temporary => false
			[protected] _columnKeys => [
				'type' => null,
				'baseType' => null,
				'length' => null,
				'precision' => null,
				'null' => null,
				'default' => null,
				'comment' => null
			]
			[protected] _columnExtras => [
				'string' => [
					'fixed' => null
				],
				'integer' => [
					'unsigned' => null,
					'autoIncrement' => null
				],
				'biginteger' => [
					'unsigned' => null,
					'autoIncrement' => null
				],
				'decimal' => [
					'unsigned' => null
				],
				'float' => [
					'unsigned' => null
				]
			]
			[protected] _indexKeys => [
				'type' => null,
				'columns' => [],
				'length' => [],
				'references' => [],
				'update' => 'restrict',
				'delete' => 'restrict'
			]
			[protected] _validIndexTypes => [
				(int) 0 => 'index',
				(int) 1 => 'fulltext'
			]
			[protected] _validConstraintTypes => [
				(int) 0 => 'primary',
				(int) 1 => 'unique',
				(int) 2 => 'foreign'
			]
			[protected] _validForeignKeyActions => [
				(int) 0 => 'cascade',
				(int) 1 => 'setNull',
				(int) 2 => 'setDefault',
				(int) 3 => 'noAction',
				(int) 4 => 'restrict'
			]
		},
		'primaryKey' => [
			(int) 0 => 'id'
		],
		'displayField' => 'id',
		'singularVar' => 'stockValue',
		'pluralVar' => 'stockValues',
		'singularHumanName' => 'Stock Value',
		'pluralHumanName' => 'Stock Values',
		'fields' => [
			(int) 0 => 'id',
			(int) 1 => 'shop_stock_id',
			(int) 2 => 'shop_product_id',
			(int) 3 => 'value',
			(int) 4 => 'last_transfer_in',
			(int) 5 => 'last_transfer_out',
			(int) 6 => 'created',
			(int) 7 => 'modified'
		],
		'associations' => [
			'BelongsTo' => [
				'ShopStocks' => [
					'property' => 'shop_stock',
					'variable' => 'shopStocks',
					'primaryKey' => [
						(int) 0 => 'id'
					],
					'displayField' => 'title',
					'foreignKey' => 'shop_stock_id',
					'alias' => 'ShopStocks',
					'controller' => 'ShopStocks',
					'fields' => [
						(int) 0 => 'id',
						(int) 1 => 'title',
						(int) 2 => 'is_default'
					],
					'navLink' => true
				],
				'ShopProducts' => [
					'property' => 'shop_product',
					'variable' => 'shopProducts',
					'primaryKey' => [
						(int) 0 => 'id'
					],
					'displayField' => 'title',
					'foreignKey' => 'shop_product_id',
					'alias' => 'ShopProducts',
					'controller' => 'ShopProducts',
					'fields' => [
						(int) 0 => 'id',
						(int) 1 => 'parent_id',
						(int) 2 => 'type',
						(int) 3 => 'shop_category_id',
						(int) 4 => 'sku',
						(int) 5 => 'title',
						(int) 6 => 'slug',
						(int) 7 => 'teaser_html',
						(int) 8 => 'desc_html',
						(int) 9 => 'preview_image_file',
						(int) 10 => 'featured_image_file',
						(int) 11 => 'image_files',
						(int) 12 => 'is_published',
						(int) 13 => 'publish_start_date',
						(int) 14 => 'publish_end_date',
						(int) 15 => 'is_buyable',
						(int) 16 => 'priority',
						(int) 17 => 'price',
						(int) 18 => 'price_net',
						(int) 19 => 'tax_rate',
						(int) 20 => 'meta_keywords',
						(int) 21 => 'meta_description',
						(int) 22 => 'custom1',
						(int) 23 => 'custom2',
						(int) 24 => 'custom3',
						(int) 25 => 'custom4',
						(int) 26 => 'custom5',
						(int) 27 => 'view_template',
						(int) 28 => 'modified',
						(int) 29 => 'created'
					],
					'navLink' => true
				]
			]
		],
		'keyFields' => [
			'shop_stock_id' => 'shopStocks',
			'shop_product_id' => 'shopProducts'
		],
		'plugin' => 'Shop',
		'action' => 'index'
	],
	'modelObject' => object(Shop\Model\Table\StockValuesTable) {

		'registryAlias' => 'Shop.StockValues',
		'table' => 'shop_stock_values',
		'alias' => 'StockValues',
		'entityClass' => 'Shop\Model\Entity\StockValue',
		'associations' => [
			(int) 0 => 'shopstocks',
			(int) 1 => 'shopproducts'
		],
		'behaviors' => [
			(int) 0 => 'Timestamp'
		],
		'defaultConnection' => 'default',
		'connectionName' => 'default'
	
	},
	'modelClass' => 'Shop.StockValues',
	'schema' => object(Cake\Database\Schema\TableSchema) {
		[protected] _table => 'shop_stock_values'
		[protected] _columns => [
			'id' => [
				'type' => 'integer',
				'length' => (int) 11,
				'unsigned' => true,
				'null' => false,
				'default' => null,
				'comment' => '',
				'autoIncrement' => true,
				'baseType' => null,
				'precision' => null
			],
			'shop_stock_id' => [
				'type' => 'integer',
				'length' => (int) 11,
				'unsigned' => true,
				'null' => false,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'autoIncrement' => null
			],
			'shop_product_id' => [
				'type' => 'integer',
				'length' => (int) 11,
				'unsigned' => true,
				'null' => false,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'autoIncrement' => null
			],
			'value' => [
				'type' => 'integer',
				'length' => (int) 11,
				'unsigned' => false,
				'null' => false,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'autoIncrement' => null
			],
			'last_transfer_in' => [
				'type' => 'datetime',
				'length' => null,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null
			],
			'last_transfer_out' => [
				'type' => 'datetime',
				'length' => null,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null
			],
			'created' => [
				'type' => 'datetime',
				'length' => null,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null
			],
			'modified' => [
				'type' => 'datetime',
				'length' => null,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null
			]
		]
		[protected] _typeMap => [
			'id' => 'integer',
			'shop_stock_id' => 'integer',
			'shop_product_id' => 'integer',
			'value' => 'integer',
			'last_transfer_in' => 'datetime',
			'last_transfer_out' => 'datetime',
			'created' => 'datetime',
			'modified' => 'datetime'
		]
		[protected] _indexes => []
		[protected] _constraints => [
			'primary' => [
				'type' => 'primary',
				'columns' => [
					(int) 0 => 'id'
				],
				'length' => []
			],
			'shop_product_id_UNIQUE' => [
				'type' => 'unique',
				'columns' => [
					(int) 0 => 'shop_product_id'
				],
				'length' => []
			]
		]
		[protected] _options => [
			'engine' => 'InnoDB',
			'collation' => 'utf8_general_ci'
		]
		[protected] _temporary => false
		[protected] _columnKeys => [
			'type' => null,
			'baseType' => null,
			'length' => null,
			'precision' => null,
			'null' => null,
			'default' => null,
			'comment' => null
		]
		[protected] _columnExtras => [
			'string' => [
				'fixed' => null
			],
			'integer' => [
				'unsigned' => null,
				'autoIncrement' => null
			],
			'biginteger' => [
				'unsigned' => null,
				'autoIncrement' => null
			],
			'decimal' => [
				'unsigned' => null
			],
			'float' => [
				'unsigned' => null
			]
		]
		[protected] _indexKeys => [
			'type' => null,
			'columns' => [],
			'length' => [],
			'references' => [],
			'update' => 'restrict',
			'delete' => 'restrict'
		]
		[protected] _validIndexTypes => [
			(int) 0 => 'index',
			(int) 1 => 'fulltext'
		]
		[protected] _validConstraintTypes => [
			(int) 0 => 'primary',
			(int) 1 => 'unique',
			(int) 2 => 'foreign'
		]
		[protected] _validForeignKeyActions => [
			(int) 0 => 'cascade',
			(int) 1 => 'setNull',
			(int) 2 => 'setDefault',
			(int) 3 => 'noAction',
			(int) 4 => 'restrict'
		]
	},
	'primaryKey' => [
		(int) 0 => 'id'
	],
	'displayField' => 'id',
	'singularVar' => 'stockValue',
	'pluralVar' => 'stockValues',
	'singularHumanName' => 'Stock Value',
	'pluralHumanName' => 'Stock Values',
	'fields' => object(Cake\Collection\Collection) {

		'count' => (int) 7
	
	},
	'associations' => [
		'BelongsTo' => [
			'ShopStocks' => [
				'property' => 'shop_stock',
				'variable' => 'shopStocks',
				'primaryKey' => [
					(int) 0 => 'id'
				],
				'displayField' => 'title',
				'foreignKey' => 'shop_stock_id',
				'alias' => 'ShopStocks',
				'controller' => 'ShopStocks',
				'fields' => [
					(int) 0 => 'id',
					(int) 1 => 'title',
					(int) 2 => 'is_default'
				],
				'navLink' => true
			],
			'ShopProducts' => [
				'property' => 'shop_product',
				'variable' => 'shopProducts',
				'primaryKey' => [
					(int) 0 => 'id'
				],
				'displayField' => 'title',
				'foreignKey' => 'shop_product_id',
				'alias' => 'ShopProducts',
				'controller' => 'ShopProducts',
				'fields' => [
					(int) 0 => 'id',
					(int) 1 => 'parent_id',
					(int) 2 => 'type',
					(int) 3 => 'shop_category_id',
					(int) 4 => 'sku',
					(int) 5 => 'title',
					(int) 6 => 'slug',
					(int) 7 => 'teaser_html',
					(int) 8 => 'desc_html',
					(int) 9 => 'preview_image_file',
					(int) 10 => 'featured_image_file',
					(int) 11 => 'image_files',
					(int) 12 => 'is_published',
					(int) 13 => 'publish_start_date',
					(int) 14 => 'publish_end_date',
					(int) 15 => 'is_buyable',
					(int) 16 => 'priority',
					(int) 17 => 'price',
					(int) 18 => 'price_net',
					(int) 19 => 'tax_rate',
					(int) 20 => 'meta_keywords',
					(int) 21 => 'meta_description',
					(int) 22 => 'custom1',
					(int) 23 => 'custom2',
					(int) 24 => 'custom3',
					(int) 25 => 'custom4',
					(int) 26 => 'custom5',
					(int) 27 => 'view_template',
					(int) 28 => 'modified',
					(int) 29 => 'created'
				],
				'navLink' => true
			]
		]
	],
	'keyFields' => [
		'shop_stock_id' => 'shopStocks',
		'shop_product_id' => 'shopProducts'
	],
	'plugin' => 'Shop',
	'action' => 'index',
	'done' => [
		(int) 0 => 'ShopStocks',
		(int) 1 => 'ShopProducts'
	],
	'data' => [
		'ShopStocks' => [
			'property' => 'shop_stock',
			'variable' => 'shopStocks',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'title',
			'foreignKey' => 'shop_stock_id',
			'alias' => 'ShopStocks',
			'controller' => 'ShopStocks',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'title',
				(int) 2 => 'is_default'
			],
			'navLink' => true
		],
		'ShopProducts' => [
			'property' => 'shop_product',
			'variable' => 'shopProducts',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'title',
			'foreignKey' => 'shop_product_id',
			'alias' => 'ShopProducts',
			'controller' => 'ShopProducts',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'parent_id',
				(int) 2 => 'type',
				(int) 3 => 'shop_category_id',
				(int) 4 => 'sku',
				(int) 5 => 'title',
				(int) 6 => 'slug',
				(int) 7 => 'teaser_html',
				(int) 8 => 'desc_html',
				(int) 9 => 'preview_image_file',
				(int) 10 => 'featured_image_file',
				(int) 11 => 'image_files',
				(int) 12 => 'is_published',
				(int) 13 => 'publish_start_date',
				(int) 14 => 'publish_end_date',
				(int) 15 => 'is_buyable',
				(int) 16 => 'priority',
				(int) 17 => 'price',
				(int) 18 => 'price_net',
				(int) 19 => 'tax_rate',
				(int) 20 => 'meta_keywords',
				(int) 21 => 'meta_description',
				(int) 22 => 'custom1',
				(int) 23 => 'custom2',
				(int) 24 => 'custom3',
				(int) 25 => 'custom4',
				(int) 26 => 'custom5',
				(int) 27 => 'view_template',
				(int) 28 => 'modified',
				(int) 29 => 'created'
			],
			'navLink' => true
		]
	],
	'type' => 'BelongsTo',
	'details' => [
		'property' => 'shop_product',
		'variable' => 'shopProducts',
		'primaryKey' => [
			(int) 0 => 'id'
		],
		'displayField' => 'title',
		'foreignKey' => 'shop_product_id',
		'alias' => 'ShopProducts',
		'controller' => 'ShopProducts',
		'fields' => [
			(int) 0 => 'id',
			(int) 1 => 'parent_id',
			(int) 2 => 'type',
			(int) 3 => 'shop_category_id',
			(int) 4 => 'sku',
			(int) 5 => 'title',
			(int) 6 => 'slug',
			(int) 7 => 'teaser_html',
			(int) 8 => 'desc_html',
			(int) 9 => 'preview_image_file',
			(int) 10 => 'featured_image_file',
			(int) 11 => 'image_files',
			(int) 12 => 'is_published',
			(int) 13 => 'publish_start_date',
			(int) 14 => 'publish_end_date',
			(int) 15 => 'is_buyable',
			(int) 16 => 'priority',
			(int) 17 => 'price',
			(int) 18 => 'price_net',
			(int) 19 => 'tax_rate',
			(int) 20 => 'meta_keywords',
			(int) 21 => 'meta_description',
			(int) 22 => 'custom1',
			(int) 23 => 'custom2',
			(int) 24 => 'custom3',
			(int) 25 => 'custom4',
			(int) 26 => 'custom5',
			(int) 27 => 'view_template',
			(int) 28 => 'modified',
			(int) 29 => 'created'
		],
		'navLink' => true
	],
	'alias' => 'ShopProducts',
	'column' => 'modified'
]
###########################
    /tmp/bake/index-ctp.php (line 61)
########## DEBUG ##########
[
	(int) 0 => 'id',
	(int) 1 => 'shop_stock_id',
	(int) 2 => 'shop_product_id',
	(int) 3 => 'value',
	(int) 4 => 'last_transfer_in',
	(int) 5 => 'last_transfer_out',
	(int) 6 => 'created'
]
###########################
    /tmp/bake/index-ctp.php (line 62)
########## DEBUG ##########
[
	'BelongsTo' => [
		'ShopStocks' => [
			'property' => 'shop_stock',
			'variable' => 'shopStocks',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'title',
			'foreignKey' => 'shop_stock_id',
			'alias' => 'ShopStocks',
			'controller' => 'ShopStocks',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'title',
				(int) 2 => 'is_default'
			],
			'navLink' => true
		],
		'ShopProducts' => [
			'property' => 'shop_product',
			'variable' => 'shopProducts',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'title',
			'foreignKey' => 'shop_product_id',
			'alias' => 'ShopProducts',
			'controller' => 'ShopProducts',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'parent_id',
				(int) 2 => 'type',
				(int) 3 => 'shop_category_id',
				(int) 4 => 'sku',
				(int) 5 => 'title',
				(int) 6 => 'slug',
				(int) 7 => 'teaser_html',
				(int) 8 => 'desc_html',
				(int) 9 => 'preview_image_file',
				(int) 10 => 'featured_image_file',
				(int) 11 => 'image_files',
				(int) 12 => 'is_published',
				(int) 13 => 'publish_start_date',
				(int) 14 => 'publish_end_date',
				(int) 15 => 'is_buyable',
				(int) 16 => 'priority',
				(int) 17 => 'price',
				(int) 18 => 'price_net',
				(int) 19 => 'tax_rate',
				(int) 20 => 'meta_keywords',
				(int) 21 => 'meta_description',
				(int) 22 => 'custom1',
				(int) 23 => 'custom2',
				(int) 24 => 'custom3',
				(int) 25 => 'custom4',
				(int) 26 => 'custom5',
				(int) 27 => 'view_template',
				(int) 28 => 'modified',
				(int) 29 => 'created'
			],
			'navLink' => true
		]
	]
]
###########################
</pre>