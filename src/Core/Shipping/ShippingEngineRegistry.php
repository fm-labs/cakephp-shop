<?php
declare(strict_types=1);

namespace Shop\Core\Shipping;

use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;

/**
 * Registry of loaded log engines
 */
class ShippingEngineRegistry extends ObjectRegistry
{
    /**
     * Resolve a payment engine classname.
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

        return App::className($class, 'Core/Shipping/Engine', 'Shipping');
    }

    /**
     * Throws an exception when a payment engine is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the payment engine is missing in.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new RuntimeException(sprintf('Could not load class %s', $class));
    }

    /**
     * Create the payment engine instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string|\Psr\Log\LoggerInterface $class The classname or object to make.
     * @param string $alias The alias of the object.
     * @param array $settings An array of settings to use for the payment engine.
     * @return \Psr\Log\LoggerInterface The constructed payment engine class.
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
            $instance = new $class($settings);
        }

        if ($instance instanceof ShippingEngineInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'ShippingEngine must implement ShippingEngineInterface.'
        );
    }

    /**
     * Remove a single payment engine from the registry.
     *
     * @param string $name The payment engine name.
     * @return void
     */
    public function unload($name)
    {
        unset($this->_loaded[$name]);
    }
}
