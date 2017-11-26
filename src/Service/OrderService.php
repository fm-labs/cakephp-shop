<?php

namespace Shop\Service;

use Cake\Event\EventListenerInterface;

class OrderService implements EventListenerInterface
{

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [];
    }
}