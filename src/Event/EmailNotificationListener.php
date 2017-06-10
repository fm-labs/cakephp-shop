<?php

namespace Shop\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Mailer\Email;

class EmailNotificationListener implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit',
            'Shop.Model.Order.afterConfirm' => 'afterOrderConfirm',
        ];
    }

    public function afterOrderSubmit(Event $event)
    {

        $ShopOrders = $event->subject();

        $orderId = $event->data['order']['id'];
        $order = $ShopOrders
            ->find('order', ['ShopOrders.id' => $orderId]);

        if (!$order) {
            Log::error('Unable to send order notification: Order not found [ID:' . $orderId . ']', ['mail', 'shop']);

            return false;
        }

        $mailer = new \Mailman\Mailer\MailmanMailer();

        // Email to Owner
        try {
            $email = new Email('shop_notify');
            $email->subject("Neue Webshop Bestellung " . $order->nr_formatted);
            $email->template('Shop.merchant/order_submit');
            $email->viewVars(['order' => $order]);
            $mailer->sendEmail($email);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['email', 'shop']);
        }

        // Email to User
        try {
            $email = new Email('shop_customer_notify');
            $email
                ->subject("Ihre Bestellung " . $order->nr_formatted)
                ->to($order->shop_customer->email)
                //->emailFormat('text')
                ->template('Shop.customer/order_submit')
                ->viewVars(['order' => $order]);

            $mailer->sendEmail($email);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['email', 'shop']);
        }
    }

    public function afterOrderConfirm(Event $event)
    {

        $ShopOrders = $event->subject();

        $orderId = $event->data['order']['id'];
        $order = $ShopOrders
            ->find('order', ['ShopOrders.id' => $orderId]);

        if (!$order) {
            Log::error('Unable to send order confirmation: Order not found [ID:' . $orderId . ']', ['mail', 'shop']);

            return false;
        }

        $mailer = new \Mailman\Mailer\MailmanMailer();

        // Email to Owner
        try {
            $email = new Email('shop_notify');
            $email->subject("Neue Webshop Bestellung " . $order->nr_formatted);
            $email->template('Shop.merchant/order_submit');
            $email->viewVars(['order' => $order]);
            $mailer->sendEmail($email);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['email', 'shop']);
        }

        // Email to User
        try {
            $email = new Email('shop_customer_notify');
            $email
                ->subject("Ihre Bestellung " . $order->nr_formatted)
                ->to($order->shop_customer->email)
                //->emailFormat('text')
                ->template('Shop.customer/order_submit')
                ->viewVars(['order' => $order]);

            $mailer->sendEmail($email);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage(), ['email', 'shop']);
        }
    }
}
