<?php
declare(strict_types=1);

namespace Shop\Core\Checkout\Step;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Checkout\CheckoutStepInterface;

/**
 * Class BaseStep
 *
 * @package Shop\Core\Checkout\Step
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
abstract class BaseStep implements EventListenerInterface, CheckoutStepInterface
{
    protected string $id;

    /**
     * @var \Shop\Controller\Component\CheckoutComponent
     */
    public $Checkout;

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     */
    public function __construct(CheckoutComponent $Checkout)
    {
        $this->Checkout =& $Checkout;
        $this->Checkout->getController()->getEventManager()->on($this);
        $this->initialize();
    }

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'Shop.Checkout.beforeStep' => 'beforeStep',
            'Shop.Checkout.afterStep' => 'afterStep',
            'Controller.beforeRedirect' => 'beforeRedirect',
        ];
    }

    /**
     * Initialize method
     */
    public function initialize()
    {
        // Override in subclasses
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function beforeStep(Event $event)
    {
        //debug("beforeStep " . $this->getId());
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function afterStep(Event $event)
    {
        //debug("afterStep " . $this->getId());
    }

    /**
     * @param \Cake\Event\Event $event
     */
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
            $className = static::class;
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
     * Export step description.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->getId(),
            'title' => $this->getTitle(),
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
