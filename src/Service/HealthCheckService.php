<?php

namespace Shop\Service;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cupcake\Health\HealthManager;
use Shop\Health\Mpay24ConfigHealthCheck;

class HealthCheckService implements EventListenerInterface
{

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return ['Health.beforeCheck' => 'beforeHealthCheck'];
    }

    public function beforeHealthCheck(EventInterface $event)
    {
        /** @var HealthManager $hm */
        $hm = $event->getSubject();
        $hm->addCheck('shop_payment_mpay24_config', new Mpay24ConfigHealthCheck());
    }
}