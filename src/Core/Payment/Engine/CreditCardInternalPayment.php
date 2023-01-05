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
 * Class CreditCardInternalPayment
 *
 * @package Shop\Core\Payment\Engine
 */
class CreditCardInternalPayment implements PaymentEngineInterface
{
    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        $order = $Checkout->getOrder();
        foreach (['payment_info_1', 'payment_info_2', 'payment_info_3'] as $field) {
            if (!isset($order[$field]) || empty($order[$field])) {
                return false;
            }
        }

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

            if (isset($data['cc_brand']) && isset($data['cc_number'])) {
                $data['payment_info_1'] = sprintf("%s:%s", $data['cc_brand'], $data['cc_number']);
            }
            if (isset($data['cc_holder_name'])) {
                $data['payment_info_2'] = $data['cc_holder_name'];
            }
            if (isset($data['cc_expires_at'])) {
                $data['payment_info_3'] = $data['cc_expires_at'];
            }

            $order = $Checkout->getOrder();
            $order->setAccess(['cc_brand', 'cc_number', 'cc_holder_name', 'cc_expires_at', 'payment_type', 'payment_info_1', 'payment_info_2', 'payment_info_3'], true);
            $order = $Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'paymentCreditCardInternal']);

            if ($Checkout->setOrder($order, true)) {
                return $Checkout->redirectNext();
            }
        }

        return $Checkout->getController()->render('payment_credit_card_internal');
    }

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return \Cake\Http\Response|null
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order)
    {
        if ($order->status == ShopOrdersTable::ORDER_STATUS_SUBMITTED || $order->status == ShopOrdersTable::ORDER_STATUS_PENDING) {
            $Payment->ShopOrders->updateOrderStatus($order, ShopOrdersTable::ORDER_STATUS_CONFIRMED);
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

    /**
     * @inheritDoc
     */
    public function cancel(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        return $transaction;
    }
}
