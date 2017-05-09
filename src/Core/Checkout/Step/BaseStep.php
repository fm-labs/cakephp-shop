<?php

namespace Shop\Core\Checkout\Step;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class BaseStep
 *
 * @package Shop\Core\Checkout\Step
 * @property ShopOrdersTable $ShopOrders
 */
abstract class BaseStep implements EventListenerInterface
{
    public $Checkout;

    public function __construct(CheckoutComponent $Checkout)
    {
        $this->Checkout =& $Checkout;
        $this->Checkout->getController()->eventManager()->on($this);
        $this->initialize();
    }

    public function implementedEvents()
    {
        return [
            'Shop.Checkout.beforeStep' => 'beforeStep',
            'Shop.Checkout.afterStep' => 'afterStep',
            'Controller.beforeRedirect' => 'beforeRedirect'
        ];
    }

    public function initialize()
    {
        // Override in subclasses
    }

    public function backgroundExecute()
    {
        // Override in subclasses
    }

    public function beforeStep(Event $event)
    {

        //debug("beforeStep " . $this->getId());
    }

    public function afterStep(Event $event)
    {

        //debug("afterStep " . $this->getId());
        $this->backgroundExecute();
    }

    public function beforeRedirect(Event $event)
    {
        //debug("beforeRedirect " . $this->getId());
    }

    /**
     * Get default step id derived from class name.
     *
     * @return string
     */
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

    /**
     * Get default title derived from step id.
     *
     * @return string
     */
    public function getTitle()
    {
        return Inflector::humanize(Text::slug($this->getId()));
    }

    /**
     * Get default checkout controller step url.
     *
     * @return array
     */
    public function getUrl()
    {
        return ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => $this->getId()];
    }

    /**
     * Export step description.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->getId(),
            'title' => $this->getTitle(),
            'url'   => $this->getUrl()
        ];
    }

    /**
     * Convenience method to log to 'shop' and 'checkout' log stream.
     *
     * @param $msg
     * @param int $level
     */
    public function log($msg, $level = LOG_INFO)
    {
        $msg = sprintf("[%s] %s", $this->getId(), $msg);
        Log::write($level, $msg, ['shop', 'checkout']);
    }

}