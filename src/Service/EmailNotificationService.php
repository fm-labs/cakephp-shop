<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Log\Log;
use Shop\Mailer\ShopCustomerMailer;
use Shop\Mailer\ShopOwnerMailer;

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
    public function implementedEvents(): array
    {
        return [
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit',
            //'Shop.Model.Order.afterConfirm' => 'afterOrderConfirm',
        ];
    }

    /**
     * @param \Cake\Event\Event $event
     * @return void
     */
    public function afterOrderSubmit(Event $event)
    {
        $ShopOrders = $event->getSubject();

        $orderId = $event->getData('order')['id'];
        $order = $ShopOrders
            ->find('order', ['ShopOrders.id' => $orderId])
            ->first();

        if (!$order) {
            Log::error('Unable to send order notification: Order not found [ID:' . $orderId . ']', ['mail', 'shop']);

            return;
        }

        // Email to User
        try {
            (new ShopCustomerMailer())->send('orderSubmission', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }

        // Email to Owner
        try {
            (new ShopOwnerMailer())->send('orderSubmissionNotify', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }
    }

    /**
     * @param \Cake\Event\Event $event
     * @return void
     */
    public function afterOrderConfirm(Event $event)
    {
        $ShopOrders = $event->getSubject();

        $orderId = $event->getData('order')['id'];
        $order = $ShopOrders
            ->find('order', ['ShopOrders.id' => $orderId])
            ->first();

        if (!$order) {
            Log::error('Unable to send order confirmation: Order not found [ID:' . $orderId . ']', ['mail', 'shop']);

            return;
        }

        // Email to User
        try {
            (new ShopCustomerMailer())->send('orderConfirmation', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }

        // Email to Owner
        try {
            (new ShopOwnerMailer())->send('orderConfirmationNotify', [$order]);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['shop']);
        }
    }
}
