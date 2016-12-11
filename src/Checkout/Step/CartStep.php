<?php

namespace Shop\Checkout\Step;


use Cake\Controller\Controller;
use Shop\Checkout\CheckoutStepInterface;

class CartStep extends BaseStep implements CheckoutStepInterface
{
    public function isComplete()
    {
        return ($this->Checkout->Cart->getOrder() && $this->Checkout->Cart->getItemsCount() > 0) ? true : false;
    }

    public function execute(Controller $controller)
    {
        $controller->render('cart');
    }
}