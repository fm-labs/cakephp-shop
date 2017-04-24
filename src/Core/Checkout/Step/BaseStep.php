<?php

namespace Shop\Core\Checkout\Step;

use Cake\Log\Log;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Shop\Controller\Component\CheckoutComponent;

abstract class BaseStep
{
    public $Checkout;

    public function __construct(CheckoutComponent $Checkout)
    {
        $this->Checkout =& $Checkout;
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

    public function getTitle()
    {
        return Inflector::humanize(Text::slug($this->getId()));
    }

    public function getUrl()
    {
        return ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => $this->getId()];
    }

    public function log($msg, $level = LOG_INFO)
    {
        $msg = sprintf("[%s] %s", $this->getId(), $msg);
        Log::write($level, $msg, ['shop', 'checkout']);
    }
}