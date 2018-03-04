<?php

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderNotification;
use Shop\Model\Table\ShopOrderNotificationsTable;

class OrderNotificationService implements EventListenerInterface
{
    /**
     * @var ShopOrderNotificationsTable
     */
    public $Notifications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Notifications = TableRegistry::get('Shop.ShopOrderNotifications');
    }

    public function onOrderStatusUpdate(Event $event)
    {
        $this->_saveNotification($this->_createNotification($event, [
            'type' => 'status_update'
        ]));
    }

    protected function _createNotification(Event $event, array $notify = [])
    {
        $data = $event->data();
        $order = $data['order'];

        $default = [
            'shop_order_id' => $order->id,
            'type' => 'default',
            'order_status' => $order->status,
            'owner_notified' => false,
            'customer_notified' => false,
        ];

        $notify = array_merge($default, $notify);
        return $this->Notifications->newEntity($notify);
    }

    protected function _saveNotification(ShopOrderNotification $notification)
    {
        try {
            return $this->Notifications->save($notification);
        } catch (\Exception $ex) {
            Log::error("OrderNotificationService::_saveNotification: " . $ex->getMessage());
            return false;
        }
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Shop.Model.Order.statusUpdate' => 'onOrderStatusUpdate'
        ];
    }
}