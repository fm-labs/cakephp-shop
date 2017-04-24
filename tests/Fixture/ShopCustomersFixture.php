<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopCustomersFixture
 *
 */
class ShopCustomersFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'shop_customers'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'user_id' => 2, // Linked to 'Normal user' of User plugin fixture
            'email' => 'test@example.org',
            'password' => null,

            'greeting' => null,
            'first_name' => null,
            'last_name' => null,
            'street' => null,
            'zipcode' => null,
            'city' => null,
            'country' => null,
            'country_iso2' => null,
            'phone' => null,
            'fax' => null,
            'locale' => null,


            'email_verification_code' => null,
            'email_verified' => null,
            'is_guest' => null,
            'is_blocked' => null,
            'is_new' => null,
            'created' => '2015-12-20 20:10:33',
            'modified' => '2015-12-20 20:10:33',
        ],
    ];
}
