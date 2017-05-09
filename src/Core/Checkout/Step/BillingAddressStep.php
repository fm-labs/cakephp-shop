<?php

namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Log\Log;
use Cake\Network\Exception\BadRequestException;
use Shop\Core\Checkout\CheckoutStepInterface;

class BillingAddressStep extends BaseStep implements CheckoutStepInterface
{

    public function getTitle()
    {
        return __d('shop','Billing Address');
    }

    public function isComplete()
    {
        return ($this->Checkout->getOrder()->getBillingAddress()) ? true : false;
    }

    public function backgroundExecute()
    {
        // auto-create billing from shipping address
        if (!$this->isComplete() && $this->Checkout->getOrder()->getShippingAddress()) {
            $address = $this->Checkout->getOrder()->getShippingAddress();

            $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity($address->extractAddress(), ['validate' => false]);
            if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $billingAddress, 'B')) {
                $this->Checkout->reloadOrder();
            } else {
                $this->log('Failed to create billing address from shipping address');
            }
        }
    }

    public function execute(Controller $controller)
    {
        if ($this->Checkout->getOrder()->getBillingAddress()) {
            $billingAddress = $this->Checkout->getOrder()->getBillingAddress();

        } elseif ($this->Checkout->Shop->getCustomer()) {
            // prefill with customer data
            $customerData = $this->Checkout->Shop->getCustomer()->extract(['first_name', 'last_name']);
            $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity($customerData, ['validate' => false]);

        } else {
            $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity();
        }


        if ($controller->request->is(['put', 'post'])) {

            $op = $controller->request->data('_op');
            switch ($op) {
                case "billing-customer-select":
                    $addressId = $controller->request->data('customer_address_id');

                    if ($this->Checkout->ShopOrders->setOrderAddressFromCustomerAddress($this->Checkout->getOrder(), $addressId, 'B')) {
                        $controller->Flash->success(__d('shop','Billing information has been updated'));
                        return $this->Checkout->next();
                    }
                    break;

                default:

                    $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->patchEntity($billingAddress, $controller->request->data);
                    if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $billingAddress, 'B')) {
                        $controller->Flash->success(__d('shop','Billing information has been updated'));
                        return $this->Checkout->next();
                    }
                    break;
            }

        }

        $controller->set('billingAddress', $billingAddress);
        $controller->set('billingAddresses', $this->Checkout->Shop->getCustomerAddressesList());
        $controller->set('countries', $this->Checkout->Shop->getCountriesList());

        $controller->render('billing_address');
    }

}