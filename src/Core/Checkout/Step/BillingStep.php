<?php

namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Log\Log;
use Shop\Core\Checkout\CheckoutStepInterface;

class BillingStep extends BaseStep implements CheckoutStepInterface
{

    public function getTitle()
    {
        return __('Billing Address');
    }

    public function isComplete()
    {
        return ($this->Checkout->getOrder()->getBillingAddress()) ? true : false;
    }

    public function execute(Controller $controller)
    {
        $controller->loadModel('Shop.ShopAddresses');
        $controller->loadModel('Shop.ShopOrderAddresses');
        $controller->loadModel('Shop.ShopCustomerAddresses');

        $billingAddresses = [];

        if ($this->Checkout->getOrder()->getBillingAddress()) {
            $billingAddress = $this->Checkout->getOrder()->getBillingAddress();
        } else {
            $billingAddress = $controller->ShopOrderAddresses->newEntity();
        }

        /*
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
        */

        if ($controller->request->is(['put', 'post'])) {
            $billingAddress->accessible(['type', 'shop_customer_id'], false);
            //@TODO Restrict accessible properties
            $billingAddress = $controller->ShopOrderAddresses->patchEntity($billingAddress, $controller->request->data);
            $billingAddress->shop_order_id = $this->Checkout->getOrder()->id;
            $billingAddress->type = 'B';
            if ($this->Checkout->setBillingAddress($billingAddress)) {
                $controller->Flash->success(__d('shop','Billing information has been updated'));
                $this->Checkout->redirectNext();
            }
        }

        if ($controller->Shop->getCustomer() && !$controller->Shop->getCustomer()->is_guest) {
            $billingAddresses = $controller->ShopCustomerAddresses
                ->find()
                ->where(['shop_customer_id' => $controller->Shop->getCustomer()->id])
                ->all()
                ->toArray();
        }

        $controller->set('billingAddress', $billingAddress);
        $controller->set('billingAddresses', $billingAddresses);
        $controller->set('countries', $controller->loadModel('Shop.ShopCountries')->find('list')->find('published')->order(['name_de' => 'ASC'])->toArray());
        $controller->render('billing');
    }

}