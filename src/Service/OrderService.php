<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\EventListenerInterface;

class OrderService implements EventListenerInterface
{
    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [];
    }
}
