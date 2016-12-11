<?php

namespace Shop\Checkout\Step;

use Cake\Utility\Inflector;
use Shop\Controller\Component\CheckoutComponent;

abstract class BaseStep
{
    public $Checkout;

    public function __construct(CheckoutComponent &$Checkout)
    {
        $this->Checkout = $Checkout;
        $this->initialize();
    }

    public function initialize()
    {
        // Override in subclasses
    }

    public function getId()
    {
        if (!isset($this->id)) {
            $className = get_class($this);
            $className = explode('\\', $className);
            $className = array_pop($className);
            $className = substr($className, 0, -4);
            $className = Inflector::underscore($className);
            $this->id = $className;
        }
        return $this->id;
    }

    public function getUrl()
    {
        return ['controller' => 'Checkout', 'action' => 'step', 'step' => $this->getId()];
    }
}