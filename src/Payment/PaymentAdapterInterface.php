<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/11/16
 * Time: 11:13 AM
 */

namespace Shop\Payment;


use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;

interface PaymentAdapterInterface
{
    public function __construct(CheckoutComponent $Checkout);
    public function isReadyForCheckout();
    public function checkout(Controller $controller);
}