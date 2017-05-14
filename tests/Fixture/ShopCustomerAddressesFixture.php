<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopOrderAddressesFixture
 *
 */
class ShopCustomerAddressesFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'shop_customer_addresses'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'shop_customer_id' => 1,
            'company' => '',
            'taxid' => '',
            'first_name' => 'Customer1',
            'last_name' => 'Customer Name1',
            'street' => 'Customer Street 1',
            'street2' => '',
            'zipcode' => '1234',
            'city' => 'Customer Address City1',
            'country' => 'Customer Address Country1',
            'country_id' => 1,
            'created' => '2017-01-27 20:51:57',
            'modified' => '2017-01-27 20:51:57',
            'company_name' => ''
        ],
    ];
}
