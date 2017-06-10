<?php

namespace Shop\Event;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Shop\Controller\CheckoutController;
use Shop\Core\Checkout\CheckoutStepInterface;

/**
 * Class CheckoutEvent
 *
 * @package Shop\Event
 */
class CheckoutEvent extends Event
{
    /**
     * @return CheckoutStepInterface
     */
    public function getStep()
    {
        if (!isset($this->data['step'])) {
            throw new \RuntimeException('CheckoutEvent: MISSING step in event data');
        }

        return $this->data['step'];
    }

    /**
     * @return CheckoutController|Controller
     */
    public function subject()
    {
        return parent::subject();
    }
}
