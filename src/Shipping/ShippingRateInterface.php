<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/11/16
 * Time: 9:01 AM
 */

namespace Shop\Shipping;


use Shop\Controller\Component\CheckoutComponent;

interface ShippingRateInterface
{
    public function isReadyForCheckout(CheckoutComponent $Checkout);
}