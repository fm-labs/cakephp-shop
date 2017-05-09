<?php

namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\StaticConfigTrait;
use Cake\Network\Exception\NotFoundException;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Shipping\ShippingEngineInterface;
use Shop\Core\Shipping\ShippingEngineRegistry;
use Shop\Lib\Shop;

class ShippingStep extends BaseStep implements CheckoutStepInterface
{

    use StaticConfigTrait;

    /**
     * @var ShippingEngineRegistry
     */
    protected $_registry;

    public $shippingMethods = [];

    public function getTitle()
    {
        return __d('shop','Shipping');
    }

    public function initialize()
    {
        $this->_registry = new ShippingEngineRegistry();
        foreach (Shop::config('Shop.Shipping.Engines') as $alias => $config) {
            // skip disabled engines
            if (!isset($config['enabled']) || $config['enabled'] !== true) {
                continue;
            }

            if (!isset(self::$_config[$alias])) {
                self::config($alias, $config);
            }
            $this->_registry->load($alias, self::config($alias));
        }

        $this->shippingMethods = self::$_config;
    }

    public function isComplete()
    {
        if (!$this->engine()) {
            return false;
        }

        return $this->engine()->isCheckoutComplete($this->Checkout);
    }

    /**
     * @return null|ShippingEngineInterface
     */
    public function engine()
    {
        $order = $this->Checkout->Cart->getOrder();
        if (!$order || !$order->shipping_type) {
            return null;
        }

        if ($this->_registry->has($order->shipping_type)) {
            return $this->_registry->get($order->shipping_type);
        }

        return null;
    }


    public function backgroundExecute()
    {
        // auto-select payment type
        if (!$this->isComplete() && count($this->shippingMethods) == 1) {
            $shippingMethodId = key($this->shippingMethods);

            if ($this->Checkout->setShippingType($shippingMethodId)) {
                $this->Checkout->reloadOrder();
            } else {
                $this->log('Shipping: Failed to auto-select shipping type ' . $shippingMethodId);
            }
        }
    }

    public function execute(Controller $controller)
    {
        $engine = $this->engine();

        if (!$engine || $controller->request->query('change_type')) {

            if ($controller->request->is(['post', 'put'])) {
                $engineName = $controller->request->data('shipping_type');

                if ($this->_registry->has($engineName)) {
                    $engine = $this->_registry->get($engineName);
                }
            }
        }

        $shippingMethods = $this->shippingMethods;
        $controller->set('shippingMethods', $shippingMethods);

        if ($engine) {
            return $engine->checkout($this->Checkout);
        }

        return $controller->render('shipping');
    }
}