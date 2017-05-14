<?php

namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\StaticConfigTrait;
use Cake\Network\Exception\NotFoundException;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Core\Payment\PaymentEngineRegistry;
use Shop\Lib\Shop;

class PaymentStep extends BaseStep implements CheckoutStepInterface
{

    use StaticConfigTrait;

    /**
     * @var PaymentEngineRegistry
     */
    protected $_registry;

    public $paymentMethods = [];

    public function getTitle()
    {
        return __d('shop','Payment Type');
    }

    public function initialize()
    {
        $this->_registry = new PaymentEngineRegistry();
        foreach (Shop::config('Shop.Payment.Engines') as $alias => $config) {
            // skip disabled engines
            if (!isset($config['enabled']) || $config['enabled'] !== true) {
                continue;
            }

            if (!isset(self::$_config[$alias])) {
                self::config($alias, $config);
            }
            $this->_registry->load($alias, self::config($alias));
        }

        $this->paymentMethods = self::$_config;
    }

    public function isComplete()
    {
        if (!$this->engine()) {
            return false;
        }

        return $this->engine()->isCheckoutComplete($this->Checkout);
    }

    /**
     * @return null|PaymentEngineInterface
     */
    public function engine()
    {
        $order = $this->Checkout->Cart->getOrder();
        if (!$order || !$order->payment_type) {
            return null;
        }

        if ($this->_registry->has($order->payment_type)) {
            return $this->_registry->get($order->payment_type);
        }

        return null;
    }

    public function backgroundExecute()
    {
        // auto-select payment type
        if (!$this->isComplete() && count($this->paymentMethods) == 1) {
            $paymentMethodId = key($this->paymentMethods);

            if ($this->Checkout->setPaymentType($paymentMethodId, [])) {
                $this->Checkout->reloadOrder();
            } else {
                $this->log('PaymentStep: Failed to auto-select payment type ' . $paymentMethodId);
            }
        }

    }

    public function execute(Controller $controller)
    {
        $engine = $this->engine();

        if (!$engine || $controller->request->query('change_type')) {

            if ($controller->request->is(['post', 'put'])) {
                $paymentType = $controller->request->data('payment_type');

                if ($this->_registry->has($paymentType)) {

                    $order = $this->Checkout->Cart->getOrder();
                    $order->payment_type = $paymentType;

                    if (!$this->Checkout->setOrder($order, true)) {
                        throw new \RuntimeException('PaymentStep: Failed to set payment type');
                    }

                    $engine = $this->_registry->get($paymentType);
                }
            } else {
                $engine = null;
            }
        }

        $paymentMethods = $this->paymentMethods;
        $controller->set('paymentMethods', $paymentMethods);

        if ($engine) {
            return $engine->checkout($this->Checkout);
        }

        return $controller->render('payment');
    }
}