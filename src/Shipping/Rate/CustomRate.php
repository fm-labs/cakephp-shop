<?php

namespace Shop\Shipping\Rate;


use Shop\Controller\Component\CheckoutComponent;
use Shop\Shipping\ShippingRateInterface;

class CustomRate implements ShippingRateInterface
{
    public function isReadyForCheckout(CheckoutComponent $Checkout)
    {
        return true;
    }
}