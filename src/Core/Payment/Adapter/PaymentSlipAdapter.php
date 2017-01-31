<?php

namespace Shop\Core\Payment\Adapter;

use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Payment\PaymentAdapterInterface;

class PaymentSlipAdapter implements PaymentAdapterInterface
{
    public function __construct(CheckoutComponent $Checkout)
    {
        $this->Checkout = $Checkout;
    }

    public function isReadyForCheckout()
    {
        return true;
    }

    public function checkout(Controller $controller)
    {
        if ($controller->request->is(['post', 'put'])) {
            $data = $controller->request->data();

            $order = $this->Checkout->getOrder();
            $order->accessible(['payment_type', 'payment_info_1', 'payment_info_2', 'payment_info_3'], true);
            $order = $this->Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'payment']);

            try {

                $this->Checkout->setOrder($order);
                $this->Checkout->redirectNext();

            } catch (\Exception $ex) {
                $controller->Flash->error(__d('shop','Please fill all the required fields'));
            }
        }

    }
}