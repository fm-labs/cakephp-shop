<?php

namespace Shop\Core\Payment\Engine;


use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Payment\PaymentEngineInterface;

class Mpay24Payment implements PaymentEngineInterface
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
            $order->payment_type = 'mpay24';

            if ($order->errors()) {
                throw new \InvalidArgumentException('Please fill all the required fields');
            }

            if ($Checkout->ShopOrders->saveOrder($order)) {
                $Checkout->setOrder($order);
                $Checkout->redirectNext();
            }
        }
    }
}