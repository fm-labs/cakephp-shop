<?php

namespace Shop\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class ShopEventListener implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Shop.Model.Order.afterSubmit' => 'afterOrderSubmit'
        ];
    }

    public function afterOrderSubmit(Event $event)
    {

    }
}