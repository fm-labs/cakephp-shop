<?php

namespace Shop\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Shop\Checkout\CheckoutStepInterface;
use Shop\Shipping\ShippingRateInterface;

class ShippingStep extends BaseStep implements CheckoutStepInterface
{

    protected $_adapters = [];

    public $shippingMethods = [];

    public function initialize()
    {
        $this->shippingMethods = Configure::read('Shop.ShippingMethods');
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
        if (!$order || !$order->shipping_type) {
            return false;
        }
        return $order->shipping_type;
    }

    public function execute(Controller $controller)
    {
        if (!$this->_getAdapterType() || $controller->request->query('change_type')) {
            $this->_executeShippingType($controller);
        }
        else {
            $this->_executeShippingAddress($controller);
        }

    }

    protected function _executeShippingType(Controller $controller)
    {

        if ($controller->request->is(['post', 'put'])) {

            $type = $controller->request->data('shipping_type');
            debug($type);
            if ($type) {
                $this->Checkout->setShippingType($type);
                $this->Checkout->redirectNext();
            } else {
                $controller->Flash->error(__d('shop','Please select your prefered shipping method'));
            }
        }

        $shippingMethods = $this->shippingMethods;
        $shippingOptions = [];
        array_walk($shippingMethods, function($val, $idx) use (&$shippingOptions) {
            $shippingOptions[$idx] = $val['name'];
        });

        $controller->set('shippingMethods', $shippingMethods);
        $controller->set('shippingOptions', $shippingOptions);
        $controller->render('shipping_type');
    }

    protected function _executeShippingAddress(Controller $controller)
    {

        $controller->loadModel('Shop.ShopAddresses');

        $shippingAddresses = [];
        $shippingAddress = $controller->ShopAddresses->newEntity();

        if ($controller->request->is(['put', 'post'])) {
            $shippingAddress->accessible(['type', 'shop_customer_id'], false);

            $shippingAddress = $controller->ShopAddresses->patchEntity($shippingAddress, $controller->request->data);
            if (!$shippingAddress->errors()) {
                $this->Checkout->setShippingAddress($shippingAddress);
                $controller->Flash->success(__d('shop','Shipping information has been updated'));
                $this->Checkout->redirectNext();
            }
        }

        if ($controller->Shop->getCustomer() && !$controller->Shop->getCustomer()->is_guest) {
            $shippingAddresses = $controller->ShopAddresses
                ->find()
                ->where(['shop_customer_id' => $controller->Shop->getCustomer()->id])
                ->all()
                ->toArray();
        }

        $controller->set('shippingAddress', $shippingAddress);
        $controller->set('shippingAddresses', $shippingAddresses);
        $controller->render('shipping');
    }

    /**
     * @param $alias
     * @return ShippingRateInterface
     */
    protected function _adapter($alias)
    {
        if (!isset($this->_adapters[$alias])) {

            if (!isset($this->shippingMethods[$alias])) {
                throw new NotFoundException('ShippingRate adapter ' . $alias . ' not found');
            }

            $sm = $this->shippingMethods[$alias];
            $className = App::className($sm['className'], 'Shipping/Rate', 'Rate');

            $this->_adapters[$alias] = new $className($this->Checkout);

        }
        return $this->_adapters[$alias];
    }
}