<?php

namespace Shop\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Shop\Mailer\CustomerMailer;
use Shop\Mailer\OwnerMailer;

/**
 * Class EmailNotificationListener
 *
 * @package Shop\Event
 */
class EmailNotificationListener implements EventListenerInterface
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit',
            'Shop.Model.Order.afterConfirm' => 'afterOrderConfirm',
        ];
    }

    /**
     * @param Event $event
     * @return void
     */
    public function afterOrderSubmit(Event $event)
    {
        $ShopOrders = $event->subject();

        $orderId = $event->data['order']['id'];
        $order = $ShopOrders
            ->find('order', ['ShopOrders.id' => $orderId]);

        if (!$order) {
            Log::error('Unable to send order notification: Order not found [ID:' . $orderId . ']', ['mail', 'shop']);

            return;
        }

        // Email to User
        try {
            (new CustomerMailer())->sendOrderSubmission($order);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }

        // Email to Owner
        try {
            (new OwnerMailer())->notifyOrderSubmission($order);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }
    }

    /**
     * @param Event $event
     * @return void
     */
    public function afterOrderConfirm(Event $event)
    {
        $ShopOrders = $event->subject();

        $orderId = $event->data['order']['id'];
        $order = $ShopOrders
            ->find('order', ['ShopOrders.id' => $orderId]);

        if (!$order) {
            Log::error('Unable to send order confirmation: Order not found [ID:' . $orderId . ']', ['mail', 'shop']);

            return;
        }

        // Email to User
        try {
            (new CustomerMailer())->sendOrderConfirmation($order);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }

        // Email to Owner
        try {
            (new OwnerMailer())->notifyOrderConfirmation($order);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }
    }
}
