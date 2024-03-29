<?php
declare(strict_types=1);

namespace Shop\Core\Checkout\Step;

use Cake\Controller\Controller;
use Cake\Core\StaticConfigTrait;
use Cake\Http\Response;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Shipping\ShippingEngineRegistry;
use Shop\Lib\Shop;

class ShippingStep extends BaseStep implements CheckoutStepInterface
{
    use StaticConfigTrait;

    /**
     * @var \Shop\Core\Shipping\ShippingEngineRegistry
     */
    protected $_registry;

    /**
     * @var array
     */
    public $shippingMethods = [];

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return __d('shop', 'Shipping');
    }

    /**
     * Initialize method
     */
    public function initialize()
    {
        $this->_registry = new ShippingEngineRegistry();
        foreach ((array)Shop::config('Shop.Shipping.Engines') as $alias => $config) {
            // skip disabled engines
            if (!isset($config['enabled']) || $config['enabled'] !== true) {
                continue;
            }

            if (!isset(self::$_config[$alias])) {
                self::setConfig($alias, $config);
            }
            $this->_registry->load($alias, self::getConfig($alias));
        }

        $this->shippingMethods = self::$_config;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        if ($this->engine()) {
            return $this->engine()->isCheckoutComplete($this->Checkout);
        }

        // auto-select shipping type
        if (count($this->shippingMethods) == 1) {
            $shippingMethodId = key($this->shippingMethods);
            if ($this->Checkout->setShippingType($shippingMethodId)) {
                $this->Checkout->reloadOrder();
                return true;
            } else {
                $this->log('PaymentStep: Failed to auto-select shipping type ' . $shippingMethodId);
            }
        }

        return false;
    }

    /**
     * @return null|\Shop\Core\Shipping\ShippingEngineInterface
     */
    public function engine()
    {
        $order = $this->Checkout->getOrder();
        if (!$order || !$order->shipping_type) {
            return null;
        }

        if ($this->_registry->has($order->shipping_type)) {
            return $this->_registry->get($order->shipping_type);
        }

        return null;
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return \Cake\Http\Response
     */
    public function execute(Controller $controller)
    {
        $engine = $this->engine();

        if (!$engine || $controller->getRequest()->getQuery('change')) {
            if ($controller->getRequest()->is(['post', 'put'])) {
                $engineName = $controller->getRequest()->getData('shipping_type');

                if ($this->_registry->has($engineName)) {
                    $engine = $this->_registry->get($engineName);
                }
            } else {
                $engine = null;
                $this->Checkout->getOrder()->shipping_type = null;
            }
        }

        $shippingMethods = $this->shippingMethods;
        $controller->set('shippingMethods', $shippingMethods);

        if ($engine) {
            $result = $engine->checkout($this->Checkout);
            if ($result instanceof Response) {
                return $result;
            }
        }

        return $controller->render('shipping');
    }
}
