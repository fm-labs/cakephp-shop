<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;

/**
 * @deprecated
 */
abstract class BaseService implements EventListenerInterface
{
    protected function _logEvent($eventName, Event $event)
    {
        Log::debug(sprintf('ShopEventListener [%s:%s] %s %s', static::class, $eventName, $event->getName(), get_class($event->getSubject())));
    }
}
