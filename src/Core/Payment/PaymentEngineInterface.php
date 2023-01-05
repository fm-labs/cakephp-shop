<?php
declare(strict_types=1);

namespace Shop\Core\Payment;

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
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout);

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return null|\Cake\Http\Response
     */
    public function checkout(CheckoutComponent $Checkout);

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return null|\Cake\Http\Response
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order);

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @return \Shop\Model\Entity\ShopOrderTransaction
     */
    public function confirm(PaymentComponent $Payment, ShopOrderTransaction $transaction);

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @return \Shop\Model\Entity\ShopOrderTransaction
     */
    public function cancel(PaymentComponent $Payment, ShopOrderTransaction $transaction);
}
