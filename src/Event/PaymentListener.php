<?php

namespace Shop\Event;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Shop\Model\Table\ShopOrdersTable;
use Shop\Model\Table\ShopOrderTransactionsTable;

class PaymentListener extends ShopEventListener
{
    public function implementedEvents()
    {
        return [
            'Shop.Payment.beforeConfirm' => 'beforeConfirm',
            'Shop.Payment.afterConfirm' => 'afterConfirm',
        ];
    }

    public function beforeConfirm(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function afterConfirm(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);

        $transaction = $event->data['transaction'];
        $orderId = $transaction->shop_order_id;

        $ShopOrders = TableRegistry::get('Shop.ShopOrders');

        $order = $ShopOrders->get($orderId, ['contain' => []]);

        switch ($transaction->status) {
            case ShopOrderTransactionsTable::STATUS_INIT:
                break;
            case ShopOrderTransactionsTable::STATUS_RESERVED:
            case ShopOrderTransactionsTable::STATUS_CONFIRMED:
                $ShopOrders->confirmOrder($order);

            case ShopOrderTransactionsTable::STATUS_ERROR:
            case ShopOrderTransactionsTable::STATUS_SUSPENDED:
            case ShopOrderTransactionsTable::STATUS_REJECTED:
            case ShopOrderTransactionsTable::STATUS_REVERSAL:
            case ShopOrderTransactionsTable::STATUS_CREDITED:
                $ShopOrders->updateStatusFromTransaction($order, $transaction);
            default:
                break;
        }
    }
}
