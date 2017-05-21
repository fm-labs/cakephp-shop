<?php

namespace Shop\Core\Checkout;


use Cake\Controller\Controller;
use Cake\Network\Response;
use Shop\Controller\Component\CheckoutComponent;

interface CheckoutStepInterface
{
    public function __construct(CheckoutComponent $Checkout);

    /**
     * Get step ID.
     * MUST be unique in checkout chain.
     *
     * @return string
     */
    public function getId();

    /**
     * Step title.
     * Internal use. Usually not shown to user.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Check if step has been completed.
     *
     * @return bool
     */
    public function isComplete();

    /**
     * Execute step in controller context.
     * Usually called when the user has been redirected to next step, or this is the active step.
     *
     * @param Controller $controller
     * @return null|Response
     */
    public function execute(Controller $controller);

    /**
     * Export step info.
     *
     * @return mixed
     */
    public function toArray();
}