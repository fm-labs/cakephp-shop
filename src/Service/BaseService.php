<?php

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

abstract class BaseService implements EventListenerInterface
{

    protected function _logEvent($eventName, Event $event)
    {
        Log::debug(sprintf('ShopEventListener [%s:%s] %s %s', get_class($this), $eventName, $event->name(), get_class($event->subject())));
    }
}
