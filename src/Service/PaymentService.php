<?php

namespace Shop\Service;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Shop\Model\Table\ShopOrdersTable;
use Shop\Model\Table\ShopOrderTransactionsTable;

/**
 * @property ShopOrdersTable $ShopOrders
 */
class PaymentService extends BaseService
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

        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');

        $order = $this->ShopOrders->get($orderId, ['contain' => []]);

        switch ($transaction->status) {
            case ShopOrderTransactionsTable::STATUS_INIT:
                break;
            case ShopOrderTransactionsTable::STATUS_RESERVED:
            case ShopOrderTransactionsTable::STATUS_CONFIRMED:
                $this->ShopOrders->confirmOrder($order);
                break;

            case ShopOrderTransactionsTable::STATUS_ERROR:
            case ShopOrderTransactionsTable::STATUS_SUSPENDED:
            case ShopOrderTransactionsTable::STATUS_REJECTED:
            case ShopOrderTransactionsTable::STATUS_REVERSAL:
            case ShopOrderTransactionsTable::STATUS_CREDITED:
                $this->ShopOrders->updateStatusFromTransaction($order, $transaction);
                break;
            default:
                break;
        }
    }
}
