<?php $this->Breadcrumbs->add(__('Stocks')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('Stock')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stock Transfers')),
    ['controller' => 'ShopStockTransfers', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock Transfer')),
    ['controller' => 'ShopStockTransfers', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __('List {0}', __('Shop Stock Values')),
    ['controller' => 'ShopStockValues', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('Shop Stock Value')),
    ['controller' => 'ShopStockValues', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="stocks index">

    <?php $fields = [
    'id','title','is_default',    ] ?>
    <?= $this->cell('Backend.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.Stocks',
        'data' => $stocks,
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
		'modelObject' => object(Shop\Model\Table\StocksTable) {

			'registryAlias' => 'Shop.Stocks',
			'table' => 'shop_stocks',
			'alias' => 'Stocks',
			'entityClass' => 'Shop\Model\Entity\Stock',
			'associations' => [
				(int) 0 => 'shopstocktransfers',
				(int) 1 => 'shopstockvalues'
			],
			'behaviors' => [],
			'defaultConnection' => 'default',
			'connectionName' => 'default'
		
		},
		'modelClass' => 'Shop.Stocks',
		'schema' => object(Cake\Database\Schema\Table) {
			[protected] _table => 'shop_stocks'
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
				'title' => [
					'type' => 'string',
					'length' => (int) 255,
					'null' => false,
					'default' => null,
					'comment' => '',
					'baseType' => null,
					'precision' => null,
					'fixed' => null
				],
				'is_default' => [
					'type' => 'boolean',
					'length' => null,
					'null' => true,
					'default' => '0',
					'comment' => '',
					'baseType' => null,
					'precision' => null
				]
			]
			[protected] _typeMap => [
				'id' => 'integer',
				'title' => 'string',
				'is_default' => 'boolean'
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
		'displayField' => 'title',
		'singularVar' => 'stock',
		'pluralVar' => 'stocks',
		'singularHumanName' => 'Stock',
		'pluralHumanName' => 'Stocks',
		'fields' => [
			(int) 0 => 'id',
			(int) 1 => 'title',
			(int) 2 => 'is_default'
		],
		'associations' => [
			'HasMany' => [
				'ShopStockTransfers' => [
					'property' => 'shop_stock_transfers',
					'variable' => 'shopStockTransfers',
					'primaryKey' => [
						(int) 0 => 'id'
					],
					'displayField' => 'id',
					'foreignKey' => 'shop_stock_id',
					'alias' => 'ShopStockTransfers',
					'controller' => 'ShopStockTransfers',
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
					'navLink' => true
				],
				'ShopStockValues' => [
					'property' => 'shop_stock_values',
					'variable' => 'shopStockValues',
					'primaryKey' => [
						(int) 0 => 'id'
					],
					'displayField' => 'id',
					'foreignKey' => 'shop_stock_id',
					'alias' => 'ShopStockValues',
					'controller' => 'ShopStockValues',
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
					'navLink' => true
				]
			]
		],
		'keyFields' => [],
		'plugin' => 'Shop',
		'action' => 'index'
	],
	'modelObject' => object(Shop\Model\Table\StocksTable) {

		'registryAlias' => 'Shop.Stocks',
		'table' => 'shop_stocks',
		'alias' => 'Stocks',
		'entityClass' => 'Shop\Model\Entity\Stock',
		'associations' => [
			(int) 0 => 'shopstocktransfers',
			(int) 1 => 'shopstockvalues'
		],
		'behaviors' => [],
		'defaultConnection' => 'default',
		'connectionName' => 'default'
	
	},
	'modelClass' => 'Shop.Stocks',
	'schema' => object(Cake\Database\Schema\Table) {
		[protected] _table => 'shop_stocks'
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
			'title' => [
				'type' => 'string',
				'length' => (int) 255,
				'null' => false,
				'default' => null,
				'comment' => '',
				'baseType' => null,
				'precision' => null,
				'fixed' => null
			],
			'is_default' => [
				'type' => 'boolean',
				'length' => null,
				'null' => true,
				'default' => '0',
				'comment' => '',
				'baseType' => null,
				'precision' => null
			]
		]
		[protected] _typeMap => [
			'id' => 'integer',
			'title' => 'string',
			'is_default' => 'boolean'
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
	'displayField' => 'title',
	'singularVar' => 'stock',
	'pluralVar' => 'stocks',
	'singularHumanName' => 'Stock',
	'pluralHumanName' => 'Stocks',
	'fields' => object(Cake\Collection\Collection) {

		'count' => (int) 3
	
	},
	'associations' => [
		'HasMany' => [
			'ShopStockTransfers' => [
				'property' => 'shop_stock_transfers',
				'variable' => 'shopStockTransfers',
				'primaryKey' => [
					(int) 0 => 'id'
				],
				'displayField' => 'id',
				'foreignKey' => 'shop_stock_id',
				'alias' => 'ShopStockTransfers',
				'controller' => 'ShopStockTransfers',
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
				'navLink' => true
			],
			'ShopStockValues' => [
				'property' => 'shop_stock_values',
				'variable' => 'shopStockValues',
				'primaryKey' => [
					(int) 0 => 'id'
				],
				'displayField' => 'id',
				'foreignKey' => 'shop_stock_id',
				'alias' => 'ShopStockValues',
				'controller' => 'ShopStockValues',
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
				'navLink' => true
			]
		]
	],
	'keyFields' => [],
	'plugin' => 'Shop',
	'action' => 'index',
	'done' => [
		(int) 0 => 'ShopStockTransfers',
		(int) 1 => 'ShopStockValues'
	],
	'data' => [
		'ShopStockTransfers' => [
			'property' => 'shop_stock_transfers',
			'variable' => 'shopStockTransfers',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'shop_stock_id',
			'alias' => 'ShopStockTransfers',
			'controller' => 'ShopStockTransfers',
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
			'navLink' => true
		],
		'ShopStockValues' => [
			'property' => 'shop_stock_values',
			'variable' => 'shopStockValues',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'shop_stock_id',
			'alias' => 'ShopStockValues',
			'controller' => 'ShopStockValues',
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
			'navLink' => true
		]
	],
	'type' => 'HasMany',
	'details' => [
		'property' => 'shop_stock_values',
		'variable' => 'shopStockValues',
		'primaryKey' => [
			(int) 0 => 'id'
		],
		'displayField' => 'id',
		'foreignKey' => 'shop_stock_id',
		'alias' => 'ShopStockValues',
		'controller' => 'ShopStockValues',
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
		'navLink' => true
	],
	'alias' => 'ShopStockValues',
	'column' => 'is_default'
]
###########################
    /tmp/bake/index-ctp.php (line 61)
########## DEBUG ##########
[
	(int) 0 => 'id',
	(int) 1 => 'title',
	(int) 2 => 'is_default'
]
###########################
    /tmp/bake/index-ctp.php (line 62)
########## DEBUG ##########
[
	'HasMany' => [
		'ShopStockTransfers' => [
			'property' => 'shop_stock_transfers',
			'variable' => 'shopStockTransfers',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'shop_stock_id',
			'alias' => 'ShopStockTransfers',
			'controller' => 'ShopStockTransfers',
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
			'navLink' => true
		],
		'ShopStockValues' => [
			'property' => 'shop_stock_values',
			'variable' => 'shopStockValues',
			'primaryKey' => [
				(int) 0 => 'id'
			],
			'displayField' => 'id',
			'foreignKey' => 'shop_stock_id',
			'alias' => 'ShopStockValues',
			'controller' => 'ShopStockValues',
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
			'navLink' => true
		]
	]
]
###########################
</pre>