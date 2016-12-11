<?php

namespace Shop\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Shop\Checkout\CheckoutStepInterface;
use Shop\Payment\PaymentAdapterInterface;
use Shop\Payment\PaymentRateInterface;

class PaymentStep extends BaseStep implements CheckoutStepInterface
{
    
    protected $_adapters = [];

    public $paymentMethods = [];

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
        if (!$this->_getAdapterType() || $controller->request->query('change_type')) {
            $this->_executePaymentType($controller);
        }
    }

    protected function _executePaymentType(Controller $controller)
    {

        if ($controller->request->is(['post', 'put'])) {
            $type = $controller->request->data('payment_type');
            debug($type);
            if ($type) {
                $this->_adapter($type)->checkout($controller);
            } else {
                $controller->Flash->error(__d('shop','Please select your prefered payment method'));
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
            $className = App::className($sm['className'], 'Payment/Adapter', 'Adapter');

            $this->_adapters[$alias] = new $className($this->Checkout);

        }
        return $this->_adapters[$alias];
    }
}