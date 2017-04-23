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
            'id' => 371,
            'shop_order_id' => 367,
            'refscope' => 'Shop.ShopProducts',
            'refid' => 539,
            'title' => 'Gutschein 50,- €',
            'amount' => 1,
            'unit' => 'x',
            'item_value_net' => 50,
            'tax_rate' => 20,
            'value_net' => 50,
            'value_tax' => 10,
            'value_total' => 60,
            'options' => null,
            'created' => '2015-12-23 08:40:04',
            'modified' => '2015-12-23 08:40:04'
        ],
    ];
}
