<?php

namespace Shop\Core\Payment\Adapter;


use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Core\Payment\PaymentAdapterInterface;

class Mpay24PaymentAdapter implements PaymentAdapterInterface
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
            $order->payment_type = 'mpay24';

            try {

                if ($order->errors()) {
                    throw new \InvalidArgumentException('Please fill all the required fields');
                }

                $this->Checkout->setOrder($order);
                $this->Checkout->redirectNext();

            } catch (\Exception $ex) {
                $controller->Flash->error($ex->getMessage());
            }
        }
    }
}