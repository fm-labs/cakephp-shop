<?php
declare(strict_types=1);

namespace Shop\Event;

use Cake\Event\Event;

/**
 * Class CheckoutEvent
 *
 * @package Shop\Event
 */
class CheckoutEvent extends Event
{
    /**
     * @return \Shop\Core\Checkout\CheckoutStepInterface
     */
    public function getStep()
    {
        if (!isset($this->data['step'])) {
            throw new \RuntimeException('CheckoutEvent: MISSING step in event data');
        }

        return $this->data['step'];
    }

    /**
     * @return \Shop\Controller\CheckoutController|\Shop\Event\Controller
     */
    public function getSubject()
    {
        return parent::getSubject();
    }
}
