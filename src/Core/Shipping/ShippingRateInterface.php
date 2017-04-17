<?php

namespace Shop\Core\Shipping;


use Shop\Controller\Component\CheckoutComponent;

interface ShippingRateInterface
{
    public function isReadyForCheckout(CheckoutComponent $Checkout);
}