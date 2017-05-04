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
            'shop_order_id' => 1,
            'shop_customer_address_id' => null,
            'type' => 'B',
            'is_company' => null,
            'taxid' => null,
            'first_name' => 'Anna',
            'last_name' => 'Testovic',
            'street' => 'Street 24',
            'street2' => null,
            'zipcode' => '1234',
            'city' => 'Debugging',
            'country' => 'Lalaland',
            'country_id' => 1,
            'created' => '2017-01-27 20:51:57',
            'modified' => '2017-01-27 20:51:57',
            'company_name' => null
        ],
    ];
}
