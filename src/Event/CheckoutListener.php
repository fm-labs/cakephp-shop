<?php

namespace Shop\Event;


use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Event\ShopEventListener;

class CheckoutListener extends ShopEventListener
{
    public function implementedEvents()
    {
        return [
            'Shop.Model.Order.afterSubmit' => 'afterSubmit',
            //'Shop.Checkout.afterSubmit' => 'afterSubmit',
        ];
    }

    public function afterSubmit(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);

        $order = $event->data['order'];

        $address = $order->getBillingAddress();
        if ($address && !$address->shop_customer_address_id) {
            if (!TableRegistry::get('Shop.ShopCustomerAddresses')->newRecordFromOrderAddress($order->shop_customer_id, $address)) {
                Log::error(sprintf('newRecordFromOrderAddress [B] failed for order %s', $order->id));
            }
        }

        $address = $order->getShippingAddress();
        if ($address && !$address->shop_customer_address_id) {
            if (!TableRegistry::get('Shop.ShopCustomerAddresses')->newRecordFromOrderAddress($order->shop_customer_id, $address)) {
                Log::error(sprintf('newRecordFromOrderAddress [S] failed for order %s', $order->id));
            }
        }

    }

}