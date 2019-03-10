<?php

namespace Shop\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrdersTable;
use Shop\Model\Table\ShopOrderTransactionsTable;
use Shop\Test\TestCase\TestPaymentEngine;

/**
 * Class PaymentControllerTest
 *
 * @package Shop\Test\TestCase\Controller
 */
class PaymentControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.shop.shop_orders',
        'plugin.shop.shop_customers',
        'plugin.shop.shop_customer_addresses',
        //'plugin.shop.shop_addresses',
        //'plugin.shop.users',
        //'plugin.shop.primary_group',
        //'plugin.shop.primary_users',
        //'plugin.shop.groups',
        //'plugin.shop.user_groups_users',
        //'plugin.shop.shop_carts',
        'plugin.shop.shop_order_items',
        'plugin.shop.shop_order_addresses',
        'plugin.shop.shop_order_transactions',
        'plugin.shop.shop_order_transaction_notifies',
        'plugin.shop.shop_products',
        'plugin.shop.shop_countries',
        //'plugin.shop.billing_address',
        //'plugin.shop.shipping_address'
        'plugin.user.users',
        'plugin.user.groups',
        'plugin.user.groups_users',
        'plugin.content.content_modules',
        'plugin.content.modules',
    ];

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * @var ShopOrderTransactionsTable
     */
    public $ShopOrderTransactions;

    /**
     * @var ShopOrderTransaction
     */
    public $transaction;

    public function setUp()
    {
        parent::setUp();

        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
        $this->ShopOrderTransactions = TableRegistry::get('Shop.ShopOrderTransactions');

        Configure::write('Shop.Payment.Engines', [
            'test' => [
                'className' => new TestPaymentEngine()
            ],
        ]);
    }

    protected function _setupNewOrder()
    {
    }

    protected function _setupNewTransaction($engine = 'test')
    {
        $transaction = $this->ShopOrderTransactions->newEntity([
            'shop_order_id' => 1,
            'type' => 'P',
            'engine' => $engine,
            'currency_code' => 'EUR',
            'value' => 120.00,
            'status' => 0
        ]);

        $this->transaction = $this->ShopOrderTransactions->save($transaction);
        if (!$this->transaction) {
            debug($transaction->errors());
            $this->fail("Failed to setup new test transaction");
        }

        return $this->transaction;
    }

    protected function _getTransaction($id)
    {
        return $this->ShopOrderTransactions->get($id);
    }

    public function testIndex()
    {
        $this->get('/shop/payment');
        $this->markTestIncomplete();
    }

    public function testConfirm()
    {
        $t = $this->_setupNewTransaction();

        $query = http_build_query([
            'test_status' => 'ok',
            'txnid' => 'TEST123'
        ]);

        $this->get('/shop/payment/confirm/' . $t->id . '?' . $query);

        $_t = $this->_getTransaction($t->id);

        $this->assertEquals('TEST123', $_t->ext_txnid);
        $this->assertEquals('PAYED', $_t->ext_status);
        $this->assertEquals(ShopOrderTransactionsTable::STATUS_CONFIRMED, $_t->status);
        $this->assertEquals(true, $_t->is_test);
    }

    public function testPay()
    {
        $this->get('/shop/payment/pay');
        $this->markTestIncomplete();
    }

    public function testSuccess()
    {
        $this->get('/shop/payment/success');
        $this->markTestIncomplete();
    }

    public function testError()
    {
        $this->get('/shop/payment/error');
        $this->markTestIncomplete();
    }

    public function testCancel()
    {
        $this->get('/shop/payment/cancel');
        $this->markTestIncomplete();
    }
}
