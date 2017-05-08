<?php

namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Log\Log;
use Cake\Network\Exception\BadRequestException;
use Shop\Core\Checkout\CheckoutStepInterface;

class ShippingAddressStep extends BaseStep implements CheckoutStepInterface
{

    public function getTitle()
    {
        return __d('shop','Shipping Address');
    }

    public function isComplete()
    {
        if (!$this->Checkout->getOrder()) {
            return false;
        }

        // check if shipping is required
        if (!$this->Checkout->ShopOrders->requiresShipping($this->Checkout->getOrder())) {
            return true;
        }

        // check if shipping address is required
        if (!$this->Checkout->ShopOrders->requiresShippingAddress($this->Checkout->getOrder())) {
            return true;
        }

        return ($this->Checkout->getOrder()->getShippingAddress()) ? true : false;
    }

    public function execute(Controller $controller)
    {
        if ($this->Checkout->getOrder()->getShippingAddress()) {
            $shippingAddress = $this->Checkout->getOrder()->getShippingAddress();

        } elseif ($this->Checkout->Shop->getCustomer()) {
            // prefill with customer data
            $customerData = $this->Checkout->Shop->getCustomer()->extract(['first_name', 'last_name']);
            $shippingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity($customerData, ['validate' => false]);

        } else {
            $shippingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity();
        }


        if ($controller->request->is(['put', 'post'])) {

            $op = $controller->request->data('_op');
            switch ($op) {
                case "shipping-customer-select":
                    $addressId = $controller->request->data('customer_address_id');

                    if ($this->Checkout->ShopOrders->setOrderAddressFromCustomerAddress($this->Checkout->getOrder(), $addressId, 'S')) {
                        $controller->Flash->success(__d('shop','Shipping address has been updated'));
                        $this->Checkout->redirectNext();
                    }
                    break;

                default:

                    $shippingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->patchEntity($shippingAddress, $controller->request->data);
                    if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $shippingAddress, 'S')) {
                        $controller->Flash->success(__d('shop','Shipping address has been updated'));
                        $this->Checkout->redirectNext();
                    }
                    break;
            }

        }

        $controller->set('shippingAddress', $shippingAddress);
        $controller->set('shippingAddresses', $this->Checkout->Shop->getCustomerAddressesList());
        $controller->set('countries', $this->Checkout->Shop->getCountriesList());

        $controller->render('shipping_address');
    }

}