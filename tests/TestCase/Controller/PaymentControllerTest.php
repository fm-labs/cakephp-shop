<?php

namespace Shop\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrdersTable;
use Shop\Model\Table\ShopOrderTransactionsTable;
use Shop\Test\TestCase\TestPaymentEngine;

/**
 * Class PaymentControllerTest
 *
 * @package Shop\Test\TestCase\Controller
 */
class PaymentControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.Shop.ShopOrders',
        'plugin.Shop.ShopCustomers',
        'plugin.Shop.ShopCustomerAddresses',
        //'plugin.Shop.shop_addresses',
        //'plugin.Shop.users',
        //'plugin.Shop.primary_group',
        //'plugin.Shop.primary_users',
        //'plugin.Shop.groups',
        //'plugin.Shop.user_groups_users',
        //'plugin.Shop.shop_carts',
        'plugin.Shop.ShopOrderItems',
        'plugin.Shop.ShopOrderAddresses',
        'plugin.Shop.ShopOrderTransactions',
        'plugin.Shop.ShopOrderTransactionNotifies',
        'plugin.Shop.ShopProducts',
        'plugin.Shop.ShopCountries',
        //'plugin.Shop.billing_address',
        //'plugin.Shop.shipping_address'
        'plugin.User.Users',
        'plugin.User.Groups',
        'plugin.User.GroupsUsers',
        'plugin.Content.ContentModules',
        'plugin.Content.Modules',
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

        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->ShopOrderTransactions = TableRegistry::getTableLocator()->get('Shop.ShopOrderTransactions');

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
            debug($transaction->getErrors());
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
