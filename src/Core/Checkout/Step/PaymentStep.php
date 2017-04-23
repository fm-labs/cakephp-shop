<?php

namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Core\Payment\PaymentAdapterInterface;
use Shop\Core\Payment\PaymentRateInterface;

class PaymentStep extends BaseStep implements CheckoutStepInterface
{
    
    protected $_adapters = [];

    public $paymentMethods = [];

    public function getTitle()
    {
        return __d('shop','Payment Type');
    }

    public function initialize()
    {
        $this->paymentMethods = Configure::read('Shop.PaymentMethods');
    }

    public function isComplete()
    {
        $type = $this->_getAdapterType();
        if (!$type) {
            return false;
        }

        return $this->_adapter($type)->isReadyForCheckout($this->Checkout);
    }

    protected function _getAdapterType()
    {
        $order = $this->Checkout->Cart->getOrder();
        if (!$order || !$order->payment_type) {
            return false;
        }
        return $order->payment_type;
    }

    public function execute(Controller $controller)
    {
        $adapterType = $this->_getAdapterType();
        if (!$adapterType) {
            $this->_executeSelectPaymentType($controller);
        } elseif ($controller->request->query('change_type')) {
            $this->_executeSelectPaymentType($controller);
        } else {
            $this->_adapter($adapterType)->checkout($controller);
        }
    }

    protected function _executeSelectPaymentType(Controller $controller)
    {

        if ($controller->request->is(['post', 'put'])) {
            $type = $controller->request->data('payment_type');

            if ($type) {
                return $this->_adapter($type)->checkout($controller);
            } else {
                $controller->Flash->error(__d('shop','Please select your preferred payment method'));
            }
        }

        $paymentMethods = $this->paymentMethods;
        $paymentOptions = [];
        array_walk($paymentMethods, function($val, $idx) use (&$paymentOptions) {
            $paymentOptions[$idx] = $val['name'];
        });

        $controller->set('paymentMethods', $paymentMethods);
        $controller->set('paymentOptions', $paymentOptions);
        $controller->render('payment');
    }

    /**
     * @param $alias
     * @return PaymentAdapterInterface
     */
    protected function _adapter($alias)
    {
        if (!isset($this->_adapters[$alias])) {

            if (!isset($this->paymentMethods[$alias])) {
                throw new NotFoundException('PaymentRate adapter ' . $alias . ' not found');
            }

            $sm = $this->paymentMethods[$alias];
            $className = App::className($sm['className'], 'Core/Payment/Adapter', 'Adapter');

            $this->_adapters[$alias] = new $className($this->Checkout);

        }
        return $this->_adapters[$alias];
    }
}