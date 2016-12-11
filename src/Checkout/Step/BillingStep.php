<?php

namespace Shop\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Log\Log;
use Shop\Checkout\CheckoutStepInterface;

class BillingStep extends BaseStep implements CheckoutStepInterface
{

    public function isComplete()
    {
        return isset($this->Checkout->billingAddress);
    }

    public function execute(Controller $controller)
    {
        $controller->loadModel('Shop.ShopAddresses');

        $billingAddresses = [];

        if ($controller->request->query('change') && $this->Checkout->billingAddress) {
            $billingAddress = $this->Checkout->billingAddress;
        } else {
            $billingAddress = $controller->ShopAddresses->newEntity();
        }

        if ($controller->Shop->getCustomer() && ($addressId = $controller->request->query('addressid'))) {
            $billingAddress = $controller->ShopAddresses->get($addressId);
            if ($billingAddress->shop_customer_id != $controller->Shop->getCustomer()->id) {
                Log::alert('Unallowed to customer address from customerID' .  $controller->Shop->getCustomer()->id . ' to addressID ' . $addressId);
            } else {
                $this->Checkout->setBillingAddress($billingAddress);
                $controller->Flash->success(__d('shop','Billing information has been updated'));
                $this->Checkout->redirectNext();
            }
        }

        if ($controller->request->is(['put', 'post'])) {
            $billingAddress->accessible(['type', 'shop_customer_id'], false);
            $billingAddress = $controller->ShopAddresses->patchEntity($billingAddress, $controller->request->data);
            if (!$billingAddress->errors()) {
                $this->Checkout->setBillingAddress($billingAddress);
                $controller->Flash->success(__d('shop','Billing information has been updated'));
                $this->Checkout->redirectNext();
            }
        }

        if ($controller->Shop->getCustomer() && !$controller->Shop->getCustomer()->is_guest) {
            $billingAddresses = $controller->ShopAddresses
                ->find()
                ->where(['shop_customer_id' => $controller->Shop->getCustomer()->id])
                ->all()
                ->toArray();
        }

        $controller->set('billingAddress', $billingAddress);
        $controller->set('billingAddresses', $billingAddresses);
        $controller->render('billing');
    }

}