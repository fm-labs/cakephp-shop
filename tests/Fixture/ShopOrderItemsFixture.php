<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopOrderItemsFixture
 *
 */
class ShopOrderItemsFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'shop_order_items'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'shop_order_id' => 1,
            'refscope' => 'Shop.ShopProducts',
            'refid' => 1,
            'title' => 'Test Item 100',
            'amount' => 1,
            'unit' => 'x',
            'item_value_net' => 100,
            'tax_rate' => 20,
            'value_net' => 100,
            'value_tax' => 20,
            'value_total' => 120,
            'options' => null,
            'created' => '2015-12-23 08:40:04',
            'modified' => '2015-12-23 08:40:04'
        ],
        [
            'id' => 2,
            'shop_order_id' => 2,
            'refscope' => 'Shop.ShopProducts',
            'refid' => 1,
            'title' => 'Test Item 100',
            'amount' => 1,
            'unit' => 'x',
            'item_value_net' => 100,
            'tax_rate' => 20,
            'value_net' => 100,
            'value_tax' => 20,
            'value_total' => 120,
            'options' => null,
            'created' => '2015-12-23 08:40:04',
            'modified' => '2015-12-23 08:40:04'
        ],
    ];
}
