<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StockValuesFixture
 *
 */
class StockValuesFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'shop_stock_values';

    /**
     * Fields
     *
     * @var array
     */
    // phpcs::disable
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'shop_stock_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'shop_product_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'value' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'last_transfer_in' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'last_transfer_out' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'shop_product_id_UNIQUE' => ['type' => 'unique', 'columns' => ['shop_product_id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // phpcs::enable

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'shop_stock_id' => 1,
            'shop_product_id' => 1,
            'value' => 1,
            'last_transfer_in' => '2016-09-04 19:01:09',
            'last_transfer_out' => '2016-09-04 19:01:09',
            'created' => '2016-09-04 19:01:09',
            'modified' => '2016-09-04 19:01:09',
        ],
    ];
}
