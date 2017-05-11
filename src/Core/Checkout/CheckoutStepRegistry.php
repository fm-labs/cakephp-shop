<?php
namespace Shop\Core\Checkout;

use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;
use Shop\Controller\Component\CheckoutComponent;

/**
 * Registry of loaded log engines
 */
class CheckoutStepRegistry extends ObjectRegistry
{
    /**
     * @var CheckoutComponent
     */
    public $Checkout;

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
    protected function _resolveClassName($class)
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
    protected function _throwMissingClassError($class, $plugin)
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
     * Remove a single checkout step from the registry.
     *
     * @param string $name The checkout step name.
     * @return void
     */
    public function unload($name)
    {
        unset($this->_loaded[$name]);
    }
}