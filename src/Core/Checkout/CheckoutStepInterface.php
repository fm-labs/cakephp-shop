<?php

namespace Shop\Core\Checkout;


use Cake\Controller\Controller;
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
     * Get url to step, where it gets invoked.
     *
     * @return mixed
     */
    public function getUrl();

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
     * Called if the step is next, but the user has not be redirected there yet.
     *
     * @return void
     */
    public function backgroundExecute();

    /**
     * Execute step in controller context.
     * Usually called when the user has been redirected to next step, or this is the active step.
     *
     * @param Controller $controller
     * @return mixed
     */
    public function execute(Controller $controller);

    /**
     * Export step info.
     *
     * @return mixed
     */
    public function toArray();
}