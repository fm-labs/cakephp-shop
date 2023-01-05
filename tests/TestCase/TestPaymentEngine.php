<?php
declare(strict_types=1);

namespace Shop\Test\TestCase;

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
     * @return bool
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
        $transaction->ext_txnid = $Payment->getController()->getRequest()->getQuery('txnid');

        switch ($Payment->getController()->getRequest()->getQuery('test_status')) {
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

    /**
     * @inheritDoc
     */
    public function cancel(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        return $transaction;
    }
}
