<?php
declare(strict_types=1);

namespace Shop\Core\Checkout\Step;

use Cake\Controller\Controller;
use Cake\Core\StaticConfigTrait;
use Cake\Http\Response;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Payment\PaymentEngineRegistry;
use Shop\Lib\Shop;

/**
 * Class PaymentStep
 *
 * @package Shop\Core\Checkout\Step
 */
class PaymentStep extends BaseStep implements CheckoutStepInterface
{
    use StaticConfigTrait;

    /**
     * @var \Shop\Core\Payment\PaymentEngineRegistry
     */
    protected $_registry;

    /**
     * @var array
     */
    protected $_allowed = [];

    /**
     * @var array
     */
    public $paymentMethods = [];

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return __d('shop', 'Payment Type');
    }

    /**
     * Initialize method
     */
    public function initialize()
    {
        $this->_registry = new PaymentEngineRegistry();
        foreach ((array)Shop::config('Shop.Payment.Engines') as $alias => $config) {
            // skip disabled engines
            if (!isset($config['enabled']) || $config['enabled'] !== true) {
                continue;
            }

            if (!isset(self::$_config[$alias])) {
                self::setConfig($alias, $config);
            }

            $this->_registry->load($alias, self::getConfig($alias));
            $this->_allowed[$alias] = true;
        }
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        if ($this->engine()) {
            return $this->engine()->isCheckoutComplete($this->Checkout);
        }

        // auto-select payment type, if there is only a single payment method available
        if (count(self::$_config) == 1) {
            $paymentMethodId = key(self::$_config);

            if ($this->Checkout->setPaymentType($paymentMethodId, [])) {
                $this->Checkout->reloadOrder();

                return true;
            } else {
                $this->log('PaymentStep: Failed to auto-select payment type ' . $paymentMethodId);
            }
        }

        return false;
    }

    /**
     * @param $type
     * @return bool
     * @TODO Merge with allow() method
     */
    public function isPaymentTypeAllowed($type)
    {
        if (!$this->_registry->has($type)) {
            return false;
        }

        if (!isset($this->_allowed[$type])) {
            return false;
        }

        return $this->_allowed[$type];
    }

    /**
     * @param $type
     * @param bool|true $state
     * @return $this
     */
    public function allow($type, $state = true)
    {
        $this->_allowed[$type] = $state;

        return $this;
    }

    /**
     * @return null|\Shop\Core\Payment\PaymentEngineInterface
     */
    public function engine()
    {
        $order = $this->Checkout->getOrder();
        if (!$order || !$order->payment_type) {
            return null;
        }

        if ($this->_registry->has($order->payment_type)) {
            return $this->_registry->get($order->payment_type);
        }

        return null;
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return \Cake\Http\Response|null
     */
    public function execute(Controller $controller)
    {
        $engine = $this->engine();
        if (!$engine || $controller->getRequest()->getData('op') == "change" || $controller->getRequest()->getQuery('change') == true) {
            if ($controller->getRequest()->is(['post', 'put'])) {
                $paymentType = $controller->getRequest()->getData('payment_type');

                if ($this->isPaymentTypeAllowed($paymentType)) {
                    $engine = $this->_registry->get($paymentType);
                }
            } else {
                $engine = null;
                $this->Checkout->getOrder()->payment_type = null;
            }
        }

        if ($engine) {
            $result = $engine->checkout($this->Checkout);
            if ($result instanceof Response) {
                return $result;
            }
        }

        $paymentTypes = [];
        foreach ($this->_allowed as $type => $state) {
            if (!$state) {
                continue;
            }
            $paymentTypes[$type] = self::$_config[$type];
        }

        $controller->set('paymentTypes', $paymentTypes);
        $controller->set('paymentMethods', $paymentTypes); // @deprecated Use 'paymentTypes' view var instead

        return $controller->render('payment');
    }
}
