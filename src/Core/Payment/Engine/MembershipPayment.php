<?php

namespace Shop\Core\Payment\Engine;

use Cake\Network\Response;
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
     * @param CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    /**
     * @param CheckoutComponent $Checkout
     * @return Response|null
     */
    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->request->is(['post', 'put'])) {
            $data = $Checkout->request->data();

            $order = $Checkout->getOrder();
            $order->accessible(['payment_type'], true);
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
     * @param PaymentComponent $Payment
     * @param ShopOrder $transaction
     * @return null|Response
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
     * @param PaymentComponent $Payment
     * @param ShopOrderTransaction $transaction
     * @return ShopOrderTransaction
     */
    public function confirm(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        return $transaction;
    }
}
