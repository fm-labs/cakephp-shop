<?php

namespace Shop\Core\Payment\Engine;

use Cake\Network\Response;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;

class PaymentSlipPayment implements PaymentEngineInterface
{
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->request->is(['post', 'put'])) {
            $data = $Checkout->request->data();

            $order = $Checkout->getOrder();
            $order->accessible(['payment_type', 'payment_info_1', 'payment_info_2', 'payment_info_3'], true);
            $order = $Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'payment']);

            if ($Checkout->ShopOrders->saveOrder($order)) {
                $Checkout->setOrder($order);
                $Checkout->redirectNext();
            }
        }

    }

    /**
     * @param PaymentComponent $Payment
     * @param ShopOrder $order
     * @return null|Response
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order)
    {
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