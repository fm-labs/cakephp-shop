<?php

namespace Shop\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

abstract class ShopEventListener implements EventListenerInterface
{

    protected function _logEvent($eventName, Event $event)
    {
        Log::debug(sprintf('ShopEventListener [%s] %s %s', $eventName, $event->name(), get_class($event->subject())));
    }
}