<?php

namespace Shop\Core\Shipping\Rate;


use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Shipping\ShippingRateInterface;

class EmailRate implements ShippingRateInterface
{

    public function isReadyForCheckout(CheckoutComponent $Checkout)
    {
        return true;
    }
}