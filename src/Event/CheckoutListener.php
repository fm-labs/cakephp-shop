<?php

namespace Shop\Event;


use Cake\Event\Event;
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
    }

}