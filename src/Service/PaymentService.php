<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Shop\Event\ShopEventLoggerTrait;
use Shop\Model\Table\ShopOrderTransactionsTable;

/**
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
class PaymentService implements EventListenerInterface
{
    use ShopEventLoggerTrait;

    public function implementedEvents(): array
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

        $transaction = $event->getData('transaction');
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
