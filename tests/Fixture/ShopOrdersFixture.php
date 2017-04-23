<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopOrdersFixture
 *
 */
class ShopOrdersFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'shop_orders'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'uuid' => '94948d75-a415-48b3-842e-3e78b4cad30b',
            'cartid' => 'd7a42648-1d07-4dcb-9ed2-13fd392e7555',
            'sessionid' => '',
            'shop_customer_id' => 1,
            'nr' => null,
            'title' => null,
            'items_value_net' => 100,
            'items_value_tax' => 20,
            'items_value_taxed' => 120,
            'shipping_type' => 'standard',
            'shipping_value_net' => 0,
            'shipping_value_tax' => 0,
            'shipping_value_taxed' => 0,
            'order_value_total' => 120,
            'status' => 0,
            'submitted' => null,
            'confirmed' => null,
            'delivered' => null,
            'invoiced' => null,
            'payed' => null,
            'customer_notes' => null,
            'staff_notes' => null,
            'billing_address_id' => 1,
            'billing_first_name' => null,
            'billing_last_name' => null,
            'billing_name' => null,
            'billing_is_company' => null,
            'billing_street' => null,
            'billing_taxid' => null,
            'billing_zipcode' => null,
            'billing_city' => null,
            'billing_country' => null,
            'shipping_address_id' => null,
            'shipping_use_billing' => true,
            'shipping_first_name' => '',
            'shipping_last_name' => '',
            'shipping_name' => null,
            'shipping_is_company' => null,
            'shipping_street' => '',
            'shipping_zipcode' => '',
            'shipping_city' => null,
            'shipping_country' => '',
            'shipping_status' => 0,
            'customer_phone' => null,
            'customer_email' => null,
            'customer_ip' => null,
            'payment_type' => 'credit_card_internal',
            'payment_info_1' => 'visa:4444333322221111',
            'payment_info_2' => 'Marc Testman',
            'payment_info_3' => '12/20',
            'payment_status' => 0,
            'is_temporary' => true,
            'is_storno' => null,
            'is_deleted' => null,
            'agree_terms' => true,
            'agree_newsletter' => null,
            'locale' => null,
            'modified' => '2015-12-23 08:44:13',
            'created' => '2015-12-23 08:37:53',
            'ordergroup' => null,
            'coupon_code' => null,
            'coupon_value' => 0
        ],
    ];
}
