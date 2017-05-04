<?php

namespace Shop\Core\Shipping;


use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;

interface ShippingEngineInterface
{
    public function isCheckoutComplete(CheckoutComponent $Checkout);
    public function checkout(CheckoutComponent $Checkout);
}