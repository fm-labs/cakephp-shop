<?php

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Log\Log;
use Shop\Mailer\CustomerMailer;
use Shop\Mailer\OwnerMailer;

/**
 * Class EmailNotificationService
 *
 * @package Shop\Event
 */
class EmailNotificationService extends BaseService
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit',
            //'Shop.Model.Order.afterConfirm' => 'afterOrderConfirm',
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
            (new CustomerMailer())->send('orderSubmission', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }

        // Email to Owner
        try {
            (new OwnerMailer())->send('orderSubmissionNotify', [$order]);
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
            (new CustomerMailer())->send('orderConfirmation', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }

        // Email to Owner
        try {
            (new OwnerMailer())->send('orderConfirmationNotify', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }
    }
}
