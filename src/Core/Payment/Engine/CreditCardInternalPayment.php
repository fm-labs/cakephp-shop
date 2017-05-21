<?php

namespace Shop\Core\Payment\Engine;

use Cake\Network\Response;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrdersTable;

class CreditCardInternalPayment implements PaymentEngineInterface
{

    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        $order = $Checkout->getOrder();
        foreach(['payment_info_1', 'payment_info_2', 'payment_info_3'] as $field) {
            if (!isset($order[$field]) || empty($order[$field])) {
                return false;
            }
        }
        return true;
    }

    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->request->is(['post', 'put'])) {
            $data = $Checkout->request->data();

            if (isset($data['cc_brand']) && isset($data['cc_number'])) {
                $data['payment_info_1'] = sprintf("%s:%s", $data['cc_brand'], $data['cc_number']);
            }
            if (isset($data['cc_holder_name'])) {
                $data['payment_info_2'] = $data['cc_holder_name'];
            }
            if (isset($data['cc_expires_at'])) {
                $data['payment_info_3'] = $data['cc_expires_at'];
            }

            $order = $Checkout->getOrder();
            $order->accessible(['cc_brand', 'cc_number', 'cc_holder_name', 'cc_expires_at', 'payment_type', 'payment_info_1', 'payment_info_2', 'payment_info_3'], true);
            $order = $Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'paymentCreditCardInternal']);


            if ($Checkout->setOrder($order, true)) {
                return $Checkout->redirectNext();
            }
        }

        return $Checkout->getController()->render('payment_credit_card_internal');
    }

    /**
     * @param PaymentComponent $Payment
     * @param ShopOrder $order
     * @return null|Response
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order)
    {
        if ($order->status == ShopOrdersTable::ORDER_STATUS_SUBMITTED || $order->status == ShopOrdersTable::ORDER_STATUS_PENDING) {
            $Payment->ShopOrders->updateOrderStatus($order, ShopOrdersTable::ORDER_STATUS_CONFIRMED);
        }

        return $Payment->redirect(['controller' => 'Orders', 'action' => 'view', $order->uuid]);
    }


    /**
     * @param PaymentComponent $Payment
     * @param ShopOrderTransaction $transaction
     * @return ShopOrderTransaction
     */
    public function confirm(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        return $transaction;
    }
}