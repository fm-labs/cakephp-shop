<?php

namespace Shop\Core\Payment;


use Cake\Network\Response;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;

/**
 * Interface PaymentEngineInterface
 *
 * @package Shop\Core\Payment
 */
interface PaymentEngineInterface
{
    /**
     * @param CheckoutComponent $Checkout
     * @return boolean
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout);

    /**
     * @param CheckoutComponent $Checkout
     * @return null|Response
     */
    public function checkout(CheckoutComponent $Checkout);

    /**
     * @param PaymentComponent $Payment
     * @param ShopOrder $order
     * @return null|Response
     */
    public function pay(PaymentComponent $Payment, ShopOrder $order, ShopOrderTransaction $transaction);
}