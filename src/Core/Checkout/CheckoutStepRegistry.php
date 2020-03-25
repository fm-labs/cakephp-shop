<?php
declare(strict_types=1);

namespace Shop\Core\Checkout;

use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;
use Shop\Controller\Component\CheckoutComponent;

/**
 * Registry of loaded log engines
 */
class CheckoutStepRegistry extends ObjectRegistry /*implements \Iterator, \SeekableIterator*/
{
    /**
     * @var \Shop\Controller\Component\CheckoutComponent
     */
    public $Checkout;

    /**
     * @var string Current iterator key
     */
    protected $_current;

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     */
    public function __construct(CheckoutComponent $Checkout)
    {
        $this->Checkout = $Checkout;
    }

    /**
     * Resolve a checkout step classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class Partial classname to resolve.
     * @return string|false Either the correct classname or false.
     */
    protected function _resolveClassName($class): ?string
    {
        if (is_object($class)) {
            return $class;
        }

        return App::className($class, 'Core/Checkout/Step', 'Step');
    }

    /**
     * Throws an exception when a checkout step is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the checkout step is missing in.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new RuntimeException(sprintf('Could not load class %s', $class));
    }

    /**
     * Create the checkout step instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string|\Psr\Log\LoggerInterface $class The classname or object to make.
     * @param string $alias The alias of the object.
     * @param array $settings An array of settings to use for the checkout step.
     * @return \Psr\Log\LoggerInterface The constructed checkout step class.
     * @throws \RuntimeException when an object doesn't implement the correct interface.
     */
    protected function _create($class, $alias, $settings)
    {
        if (is_callable($class)) {
            $class = $class($alias);
        }

        if (is_object($class)) {
            $instance = $class;
        }

        if (!isset($instance)) {
            $instance = new $class($this->Checkout, $settings);
        }

        if ($instance instanceof CheckoutStepInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'CheckoutStep must implement CheckoutStepInterface.'
        );
    }

    /**
     * @param string $objectName The name/class of the object to load.
     * @param array $config Additional settings to use when loading the object.
     * @return mixed
     */
    public function load($objectName, $config = [])
    {
        return parent::load($objectName, $config);
    }

    /**
     * Remove a single checkout step from the registry.
     *
     * @param string $objectName The checkout step name.
     * @return void
     */
    public function unload($objectName)
    {
        //parent::unload($objectName); // @TODO Use parent unload() method
        unset($this->_loaded[$objectName]);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return \Shop\Core\Checkout\CheckoutStepInterface
     * @since 5.0.0
     */
    public function current()
    {
        return $this->get($this->key());
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        if ($this->_current === null) {
            $this->rewind();
        }

        return $this->_current;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (!$this->_current || !$this->has($this->_current)) {
            return false;
        }

        return true;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->_loaded);
        $this->_current = key($this->_loaded);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $next = false; // flag indicating to exit loop on next iteration
        foreach (array_keys($this->_loaded) as $stepId) {
            if ($next === true) {
                $this->_current = $stepId;

                return;
            }

            if ($this->_current == $stepId) {
                $next = true;
            }
        }

        // last step
        $this->_current = false;
    }

    /**
     * Seeks to a position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position <p>
     * The position to seek to.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function seek($position)
    {
        if (!$this->has($position)) {
            throw new \OutOfBoundsException("Step not loaded: " . $position);
        }

        $this->_current = $position;
    }
}
