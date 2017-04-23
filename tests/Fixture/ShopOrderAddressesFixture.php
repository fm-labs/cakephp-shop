<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopOrderAddressesFixture
 *
 */
class ShopOrderAddressesFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'shop_order_addresses'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'shop_order_id' => 367,
            'shop_customer_address_id' => null,
            'type' => 'B',
            'is_company' => null,
            'taxid' => null,
            'first_name' => 'Romana ',
            'last_name' => 'Keintzel',
            'street' => 'Wiesengrund 24',
            'street2' => null,
            'zipcode' => '2130',
            'city' => 'Lanzendorf',
            'country' => 'Niederösterreich',
            'country_id' => 13,
            'created' => '2017-01-27 20:51:57',
            'modified' => '2017-01-27 20:51:57',
            'company_name' => null
        ],
    ];
}
