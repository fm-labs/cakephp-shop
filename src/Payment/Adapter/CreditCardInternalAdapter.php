<?php

namespace Shop\Payment\Adapter;

use Cake\Controller\Controller;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Payment\PaymentAdapterInterface;

class CreditCardInternalAdapter implements PaymentAdapterInterface
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

            if (isset($data['cc_brand']) && isset($data['cc_number'])) {
                $data['payment_info_1'] = sprintf("%s:%s", $data['cc_brand'], $data['cc_number']);
            }
            if (isset($data['cc_holder_name'])) {
                $data['payment_info_2'] = $data['cc_holder_name'];
            }
            if (isset($data['cc_expires_at'])) {
                $data['payment_info_3'] = $data['cc_expires_at'];
            }

            $order = $this->Checkout->getOrder();
            $order->accessible(['payment_type', 'payment_info_1', 'payment_info_2', 'payment_info_3'], true);
            $order = $this->Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'paymentCreditCardInternal']);

            try {

                $this->Checkout->setOrder($order);
                $this->Checkout->redirectNext();

            } catch (\Exception $ex) {
                $controller->Flash->error(__d('shop','Please fill all the required fields'));
            }
        }

    }
}