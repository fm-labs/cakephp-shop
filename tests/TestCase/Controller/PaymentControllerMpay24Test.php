<?php

namespace Shop\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Shop\Model\Table\ShopOrderTransactionsTable;

/**
 * Class PaymentControllerTest
 *
 * @package Shop\Test\TestCase\Controller
 */
class PaymentControllerMpay24Test extends PaymentControllerTest
{
    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();

        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->ShopOrderTransactions = TableRegistry::getTableLocator()->get('Shop.ShopOrderTransactions');

        Configure::write('Mpay24', [
            'merchantID' => '9****',
            'soapPassword' => 'foobaz',
            'test' => true,
            'debug' => true,
        ]);

        Configure::delete('Shop.Payment.Engines');
        Configure::write('Shop.Payment.Engines', [
            'mpay24' => [
                'className' => 'Shop.Mpay24Select',
            ],
        ]);
    }

    /**
     * Test confirmation with status BILLED
     */
    public function testConfirm()
    {
        $t = $this->_setupNewTransaction('mpay24');

        /**
         *
        "OPERATION": "CONFIRMATION",
        "TID": "test59171da95ebe7",
        "STATUS": "RESERVED",
        "PRICE": "56000",
        "CURRENCY": "EUR",
        "P_TYPE": "CC",
        "BRAND": "VISA",
        "MPAYTID": "3827039",
        "USER_FIELD": "d5d9641e-3612-4d0d-bc89-e1fa2766a5d2",
        "ORDERDESC": "Bestellung BE201700009",
        "CUSTOMER": "Demo User",
        "CUSTOMER_EMAIL": "demo@example.org",
        "LANGUAGE": "DE",
        "CUSTOMER_ID": "",
        "PROFILE_STATUS": "IGNORED",
        "FILTER_STATUS": "",
        "APPR_CODE": "-test-"
        },
         */

        $query = http_build_query([
            'OPERATION' => 'CONFIRMATION',
            'TID' => $t->id,
            'MPAYTID' => 'MPAY123',
            'STATUS' => 'BILLED',
            'APPR_CODE' => '-test-',
        ]);

        $this->configRequest([
            'environment' => [
                'REMOTE_ADDR' => '213.208.153.58',
            ],
        ]);
        $this->get('/shop/payment/confirm/' . $t->id . '?' . $query);

        $_t = $this->_getTransaction($t->id);

        $this->assertEquals('MPAY123', $_t->ext_txnid);
        $this->assertEquals('BILLED', $_t->ext_status);
        $this->assertEquals(ShopOrderTransactionsTable::STATUS_CONFIRMED, $_t->status);
        $this->assertEquals(true, $_t->is_test);
    }

    /**
     * Test confirmation status RESERVED
     */
    public function testConfirmReserved()
    {
        $t = $this->_setupNewTransaction('mpay24');
        $query = http_build_query([
            'OPERATION' => 'CONFIRMATION',
            'TID' => $t->id,
            'MPAYTID' => 'MPAY123',
            'STATUS' => 'RESERVED',
            'APPR_CODE' => '-test-',
        ]);

        $this->configRequest([
            'environment' => [
                'REMOTE_ADDR' => '213.208.153.58',
            ],
        ]);
        $this->get('/shop/payment/confirm/' . $t->id . '?' . $query);

        $_t = $this->_getTransaction($t->id);

        $this->assertEquals('MPAY123', $_t->ext_txnid);
        $this->assertEquals('RESERVED', $_t->ext_status);
        $this->assertEquals(ShopOrderTransactionsTable::STATUS_RESERVED, $_t->status);
    }

    /**
     * https://docs.mpay24.com/docs/test-and-productive-system
     */
    public function _testConfirmIpSecurity()
    {
        $t = $this->_setupNewTransaction('mpay24');
        $query = http_build_query([
            'OPERATION' => 'CONFIRMATION',
            'TID' => $t->id,
            'MPAYTID' => 'MPAY123',
            'STATUS' => 'RESERVED',
            'APPR_CODE' => '-test-',
        ]);

        // test confirmations from server with ip 213.208.153.58
        $this->configRequest([
            'environment' => [
                'REMOTE_ADDR' => '213.208.153.58',
            ],
        ]);
        $this->get('/shop/payment/confirm/' . $t->id . '?' . $query);
        $this->assertResponseSuccess();

        // real confirmations from server with ip 213.164.25.245
        $this->configRequest([
            'environment' => [
                'REMOTE_ADDR' => '213.164.25.245',
            ],
        ]);
        $this->get('/shop/payment/confirm/' . $t->id . '?' . $query);
        $this->assertResponseSuccess();

        // do not process from other ips
        $this->configRequest([
            'environment' => [
                'REMOTE_ADDR' => '127.0.1.1',
            ],
        ]);
        $this->get('/shop/payment/confirm/' . $t->id . '?' . $query);
        $this->assertResponseError();
    }
}
