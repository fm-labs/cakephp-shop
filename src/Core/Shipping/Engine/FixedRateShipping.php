<?php

namespace Shop\Core\Shipping\Engine;

use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Shipping\ShippingEngineInterface;

class FixedRateShipping implements ShippingEngineInterface
{
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->getController()->getRequest()->is(['post', 'put'])) {
            $data = $Checkout->getController()->getRequest()->getData();

            $order = $Checkout->getOrder();
            $order->shipping_type = 'fixed';

            if ($Checkout->ShopOrders->saveOrder($order)) {
                $Checkout->setOrder($order);
                $Checkout->redirectNext();
            }
        }
    }
}
