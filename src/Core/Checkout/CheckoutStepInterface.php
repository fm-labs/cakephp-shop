<?php

namespace Shop\Core\Checkout;


use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;

interface CheckoutStepInterface
{
    public function __construct(CheckoutComponent $Checkout);

    public function getId();
    public function getUrl();
    public function getTitle();

    public function execute(Controller $controller);
    public function isComplete();
}