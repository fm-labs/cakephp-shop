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
            'uuid' => '11148d75-a415-48b3-842e-3e78b4cad30b',
            'cartid' => '11142648-1d07-4dcb-9ed2-13fd392e7555',
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
            'shipping_use_billing' => true,
            'customer_phone' => null,
            'customer_email' => null,
            'customer_ip' => null,
            'payment_type' => null,
            'payment_info_1' => null,
            'payment_info_2' => null,
            'payment_info_3' => null,
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
        [
            'id' => 2,
            'uuid' => '22248d75-a415-48b3-842e-3e78b4cad30b',
            'cartid' => '22242648-1d07-4dcb-9ed2-13fd392e7555',
            'sessionid' => '',
            'shop_customer_id' => null,
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
            'shipping_use_billing' => true,
            'customer_phone' => null,
            'customer_email' => null,
            'customer_ip' => null,
            'payment_type' => null,
            'payment_info_1' => null,
            'payment_info_2' => null,
            'payment_info_3' => null,
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
