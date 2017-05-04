<?php

namespace Shop\Core\Payment;


use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;

interface PaymentEngineInterface
{
    public function isCheckoutComplete(CheckoutComponent $Checkout);
    public function checkout(CheckoutComponent $Checkout);
}