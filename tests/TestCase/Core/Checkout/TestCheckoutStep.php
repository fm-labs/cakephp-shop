<?php
declare(strict_types=1);

namespace Shop\Test\TestCase\Core\Checkout;

use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Checkout\CheckoutStepInterface;

class TestCheckoutStep implements CheckoutStepInterface
{
    /**
     * @param CheckoutComponent $Checkout
     */
    public function __construct(CheckoutComponent $Checkout)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Get step ID.
     * MUST be unique in checkout chain.
     *
     * @return string
     */
    public function getId()
    {
        return 'test';
    }

    /**
     * Step title.
     * Internal use. Usually not shown to user.
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Test Checkout Step';
    }

    /**
     * Check if step has been completed.
     *
     * @return bool
     */
    public function isComplete()
    {
        return false;
    }

    /**
     * Execute step in controller context.
     * Usually called when the user has been redirected to next step, or this is the active step.
     *
     * @param Controller $controller
     * @return null|Response
     */
    public function execute(Controller $controller)
    {
    }

    /**
     * Export step info.
     *
     * @return mixed
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
        ];
    }
}
