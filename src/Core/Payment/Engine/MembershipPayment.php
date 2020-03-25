<?php
declare(strict_types=1);

namespace Shop\Core\Payment\Engine;

use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class MembershipPayment
 *
 * @package Shop\Core\Payment\Engine
 */
class MembershipPayment implements PaymentEngineInterface
{
    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return \Cake\Http\Response|null
     */
    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->getController()->getRequest()->is(['post', 'put'])) {
            $data = $Checkout->getController()->getRequest()->getData();

            $order = $Checkout->getOrder();
            $order->setAccess(['payment_type'], true);
            $order = $Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'payment']);

            //@TODO Check if

            if ($Checkout->ShopOrders->saveOrder($order)) {
                $Checkout->setOrder($order);

                return $Checkout->redirectNext();
            } else {
                $Checkout->getController()->Flash->error("Failed to update payment info");
            }
        }
    }

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrder $transaction
     * @return null|\Cake\Http\Response
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order)
    {
        //debug($transaction);
        //debug($order);
        //$Payment->getController()->Flash->error(__d('shop', 'Membership Payment is not activated yet'));

        if ($order->status == ShopOrdersTable::ORDER_STATUS_SUBMITTED || $order->status == ShopOrdersTable::ORDER_STATUS_PENDING) {
            // @TODO check if customer has enough credit left in membership

            $Payment->ShopOrders->updateOrderStatus($order, ShopOrdersTable::ORDER_STATUS_PAYED);
        }

        return $Payment->redirect(['controller' => 'Orders', 'action' => 'view', $order->uuid]);
    }

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @return \Shop\Model\Entity\ShopOrderTransaction
     */
    public function confirm(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        return $transaction;
    }
}
