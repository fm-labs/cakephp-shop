<?php

namespace Shop\Event;

use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Log\Log;

trait ShopEventLoggerTrait
{
    /**
     * @param string $eventName
     * @param EventInterface $event
     * @return void
     */
    protected function _logEvent(string $eventName, EventInterface $event): void
    {
        Log::debug(sprintf(
            'ShopEvent [%s:%s] %s %s',
            get_class($this), $eventName, $event->getName(), get_class($event->getSubject())
        ), ['shop']);
    }
}