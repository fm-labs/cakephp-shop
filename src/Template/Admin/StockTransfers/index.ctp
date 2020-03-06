<?php $this->Breadcrumbs->add(__d('shop','Stock Transfers')); ?>

<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Stock Transfer')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Parent Stock Transfers')),
    ['controller' => 'StockTransfers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Parent Stock Transfer')),
    ['controller' => 'StockTransfers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
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
<div class="stockTransfers index">

    <?php $fields = [
    'id','parent_id','shop_stock_id','shop_product_id','op','amount','date','comment','created','modified',    ] ?>
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.StockTransfers',
        'data' => $stockTransfers,
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
		'modelObject' => object(Shop\Model\Table\StockTransfersTable) {

			'registryAlias' => 'Shop.StockTransfers',
			'table' => 'shop_stock_transfers',
			'alias' => 'StockTransfers',
			'entityClass' => 'Shop\Model\Entity\StockTransfer',
			'associations' => [
				(int) 0 => 'parentstocktransfers',
				(int) 1 => 'shopstocks',
				(int) 2 => 'shopproducts',
				(int) 3 => 'childstocktransfers'
			],
			'behaviors' => [
				(int) 0 => 'Timestamp'
			],
			'defaultConnection' => 'default',
			'connectionName' => 'default'
		
		},
		'modelClass' => 'Shop.StockTransfers',
		'schema' => object(Cake\Database\Schema\TableSchema) {
			[protected] _table => 'shop_stock_transfers'
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
				'parent_id' => [
					'type' => 'integer',
					'length' => (int) 11,
					'unsigned' => true,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'autoIncrement' => null
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
				'op' => [
					'type' => 'integer',
					'length' => (int) 4,
					'unsigned' => false,
					'null' => false,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'autoIncrement' => null
				],
				'amount' => [
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
				'date' => [
					'type' => 'datetime',
					'length' => null,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null
				],
				'comment' => [
					'type' => 'string',
					'length' => (int) 255,
					'null' => true,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'fixed' => null
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
				'parent_id' => 'integer',
				'shop_stock_id' => 'integer',
				'shop_product_id' => 'integer',
				'op' => 'integer',
				'amount' => 'integer',
				'date' => 'datetime',
				'comment' => 'string',
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
		'singularVar' => 'stockTransfer',
		'pluralVar' => 'stockTransfers',
		'singularHumanName' => 'Stock Transfer',
		'pluralHumanName' => 'Stock Transfers',
		'fields' => [
			(int) 0 => 'id',
			(int) 1 => 'parent_id',
			(int) 2 => 'shop_stock_id',
			(int) 3 => 'shop_product_id',
			(int) 4 => 'op',
			(int) 5 => 'amount',
			(int) 6 => 'date',
			(int) 7 => 'comment',
			(int) 8 => 'created',
			(int) 9 => 'modified'
		],
		'associations' => [
			'BelongsTo' => [
				'ParentStockTransfers' => [
					'property' => 'parent_stock_transfer',
					'variable' => 'parentStockTransfers',
					'primaryKey' => [
						(int) 0 => 'id'
					],
					'displayField' => 'id',
					'foreignKey' => 'parent_id',
					'alias' => 'ParentStockTransfers',
					'controller' => 'StockTransfers',
					'fields' => [
						(int) 0 => 'id',
						(int) 1 => 'parent_id',
						(int) 2 => 'shop_stock_id',
						(int) 3 => 'shop_product_id',
						(int) 4 => 'op',
						(int) 5 => 'amount',
						(int) 6 => 'date',
						(int) 7 => 'comment',
						(int) 8 => 'created',
						(int) 9 => 'modified'
					],
					'navLink' => false
				],
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
			'HasMany' => [
				'ChildStockTransfers' => [
					'property' => 'child_stock_transfers',
					'variable' => 'childStockTransfers',
					'primaryKey' => [
						(int) 0 => 'id'
					],
					'displayField' => 'id',
					'foreignKey' => 'parent_id',
					'alias' => 'ChildStockTransfers',
					'controller' => 'StockTransfers',
					'fields' => [
						(int) 0 => 'id',
						(int) 1 => 'parent_id',
						(int) 2 => 'shop_stock_id',
						(int) 3 => 'shop_product_id',
						(int) 4 => 'op',
						(int) 5 => 'amount',
						(int) 6 => 'date',
						(int) 7 => 'comment',
						(int) 8 => 'created',
						(int) 9 => 'modified'
					],
					'navLink' => false
				]
			]
		],
		'keyFields' => [
			'parent_id' => 'parentStockTransfers',
			'shop_stock_id' => 'shopStocks',
			'shop_product_id' => 'shopProducts'
		],
		'plugin' => 'Shop',
		'action' => 'index'
	],
	'modelObject' => object(Shop\Model\Table\StockTransfersTable) {

		'registryAlias' => 'Shop.StockTransfers',
		'table' => 'shop_stock_transfers',
		'alias' => 'StockTransfers',
		'entityClass' => 'Shop\Model\Entity\StockTransfer',
		'associations' => [
			(int) 0 => 'parentstocktransfers',
			(int) 1 => 'shopstocks',
			(int) 2 => 'shopproducts',
			(int) 3 => 'childstocktransfers'
		],
		'behaviors' => [
			(int) 0 => 'Timestamp'
		],
		'defaultConnection' => 'default',
		'connectionName' => 'default'
	
	},
	'modelClass' => 'Shop.StockTransfers',
	'schema' => object(Cake\Database\Schema\TableSchema) {
		[protected] _table => 'shop_stock_transfers'
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
			'parent_id' => [
				'type' => 'integer',
				'length' => (int) 11,
				'unsigned' => true,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'autoIncrement' => null
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
			'op' => [
				'type' => 'integer',
				'length' => (int) 4,
				'unsigned' => false,
				'null' => false,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'autoIncrement' => null
			],
			'amount' => [
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
			'date' => [
				'type' => 'datetime',
				'length' => null,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null
			],
			'comment' => [
				'type' => 'string',
				'length' => (int) 255,
				'null' => true,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'fixed' => null
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
			'parent_id' => 'integer',
			'shop_stock_id' => 'integer',
			'shop_product_id' => 'integer',
			'op' => 'integer',
			'amount' => 'integer',
			'date' => 'datetime',
			'comment' => 'string',
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
	'singularVar' => 'stockTransfer',
	'pluralVar' => 'stockTransfers',
	'singularHumanName' => 'Stock Transfer',
	'pluralHumanName' => 'Stock Transfers',
	'fields' => object(Cake\Collection\Collection) {

		'count' => (int) 7
	
	},
	'associations' => [
		'BelongsTo' => [
			'ParentStockTransfers' => [
				'property' => 'parent_stock_transfer',
				'variable' => 'parentStockTransfers',
				'primaryKey' => [
					(int) 0 => 'id'
				],
				'displayField' => 'id',
				'foreignKey' => 'parent_id',
				'alias' => 'ParentStockTransfers',
				'controller' => 'StockTransfers',
				'fields' => [
					(int) 0 => 'id',
					(int) 1 => 'parent_id',
					(int) 2 => 'shop_stock_id',
					(int) 3 => 'shop_product_id',
					(int) 4 => 'op',
					(int) 5 => 'amount',
					(int) 6 => 'date',
					(int) 7 => 'comment',
					(int) 8 => 'created',
					(int) 9 => 'modified'
				],
				'navLink' => false
			],
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
		'HasMany' => [
			'ChildStockTransfers' => [
				'property' => 'child_stock_transfers',
				'variable' => 'childStockTransfers',
				'primaryKey' => [
					(int) 0 => 'id'
				],
				'displayField' => 'id',
				'foreignKey' => 'parent_id',
				'alias' => 'ChildStockTransfers',
				'controller' => 'StockTransfers',
				'fields' => [
					(int) 0 => 'id',
					(int) 1 => 'parent_id',
					(int) 2 => 'shop_stock_id',
					(int) 3 => 'shop_product_id',
					(int) 4 => 'op',
					(int) 5 => 'amount',
					(int) 6 => 'date',
					(int) 7 => 'comment',
					(int) 8 => 'created',
					(int) 9 => 'modified'
				],
				'navLink' => false
			]
		]
	],
	'keyFields' => [
		'parent_id' => 'parentStockTransfers',
		'shop_stock_id' => 'shopStocks',
		'shop_product_id' => 'shopProducts'
	],
	'plugin' => 'Shop',
	'action' => 'index',
	'done' => [
		(int) 0 => 'StockTransfers',
		(int) 1 => 'ShopStocks',
		(int) 2 => 'ShopProducts'
	],
	'data' => [
		'ChildStockTransfers' => [
			'property' => 'child_stock_transfers',
			'variable' => 'childStockTransfers',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'parent_id',
			'alias' => 'ChildStockTransfers',
			'controller' => 'StockTransfers',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'parent_id',
				(int) 2 => 'shop_stock_id',
				(int) 3 => 'shop_product_id',
				(int) 4 => 'op',
				(int) 5 => 'amount',
				(int) 6 => 'date',
				(int) 7 => 'comment',
				(int) 8 => 'created',
				(int) 9 => 'modified'
			],
			'navLink' => false
		]
	],
	'type' => 'HasMany',
	'details' => [
		'property' => 'child_stock_transfers',
		'variable' => 'childStockTransfers',
		'primaryKey' => [
			(int) 0 => 'id'
		],
		'displayField' => 'id',
		'foreignKey' => 'parent_id',
		'alias' => 'ChildStockTransfers',
		'controller' => 'StockTransfers',
		'fields' => [
			(int) 0 => 'id',
			(int) 1 => 'parent_id',
			(int) 2 => 'shop_stock_id',
			(int) 3 => 'shop_product_id',
			(int) 4 => 'op',
			(int) 5 => 'amount',
			(int) 6 => 'date',
			(int) 7 => 'comment',
			(int) 8 => 'created',
			(int) 9 => 'modified'
		],
		'navLink' => false
	],
	'alias' => 'ChildStockTransfers',
	'column' => 'modified'
]
###########################
    /tmp/bake/index-ctp.php (line 61)
########## DEBUG ##########
[
	(int) 0 => 'id',
	(int) 1 => 'parent_id',
	(int) 2 => 'shop_stock_id',
	(int) 3 => 'shop_product_id',
	(int) 4 => 'op',
	(int) 5 => 'amount',
	(int) 6 => 'date'
]
###########################
    /tmp/bake/index-ctp.php (line 62)
########## DEBUG ##########
[
	'BelongsTo' => [
		'ParentStockTransfers' => [
			'property' => 'parent_stock_transfer',
			'variable' => 'parentStockTransfers',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'parent_id',
			'alias' => 'ParentStockTransfers',
			'controller' => 'StockTransfers',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'parent_id',
				(int) 2 => 'shop_stock_id',
				(int) 3 => 'shop_product_id',
				(int) 4 => 'op',
				(int) 5 => 'amount',
				(int) 6 => 'date',
				(int) 7 => 'comment',
				(int) 8 => 'created',
				(int) 9 => 'modified'
			],
			'navLink' => false
		],
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
	'HasMany' => [
		'ChildStockTransfers' => [
			'property' => 'child_stock_transfers',
			'variable' => 'childStockTransfers',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'parent_id',
			'alias' => 'ChildStockTransfers',
			'controller' => 'StockTransfers',
			'fields' => [
				(int) 0 => 'id',
				(int) 1 => 'parent_id',
				(int) 2 => 'shop_stock_id',
				(int) 3 => 'shop_product_id',
				(int) 4 => 'op',
				(int) 5 => 'amount',
				(int) 6 => 'date',
				(int) 7 => 'comment',
				(int) 8 => 'created',
				(int) 9 => 'modified'
			],
			'navLink' => false
		]
	]
]
###########################
</pre>