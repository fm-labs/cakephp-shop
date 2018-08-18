<?php

namespace Shop\Test\TestCase;


use Cake\Network\Response;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrderTransactionsTable;

class TestPaymentEngine implements PaymentEngineInterface
{

    /**
     * @param CheckoutComponent $Checkout
     * @return boolean
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
    }

    /**
     * @param CheckoutComponent $Checkout
     * @return null|Response
     */
    public function checkout(CheckoutComponent $Checkout)
    {
    }

    /**
     * @param PaymentComponent $Payment
     * @param ShopOrder $order
     * @return null|Response
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order)
    {
        return $Payment->redirect('/go/to/test/payment');
    }


    /**
     * @param PaymentComponent $Payment
     * @param ShopOrderTransaction $transaction
     * @return ShopOrderTransaction
     */
    public function confirm(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        $transaction->is_test = true;
        $transaction->ext_txnid = $Payment->request->query('txnid');

        switch ($Payment->request->query('test_status')) {
            case "denied":
                $transaction->ext_status = "DENIED";
                $transaction->status = ShopOrderTransactionsTable::STATUS_REJECTED;
                break;

            case "payed":
            default:
                $transaction->ext_status = "PAYED";
                $transaction->status = ShopOrderTransactionsTable::STATUS_CONFIRMED;
                break;
        }

        return $transaction;

    }
}