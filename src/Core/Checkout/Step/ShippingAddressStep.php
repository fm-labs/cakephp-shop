<?php

namespace Shop\Core\Checkout\Step;

use Cake\Controller\Controller;
use Shop\Core\Checkout\CheckoutStepInterface;

/**
 * Class ShippingAddressStep
 *
 * @package Shop\Core\Checkout\Step
 */
class ShippingAddressStep extends BaseStep implements CheckoutStepInterface
{
    /**
     * @return null|string
     */
    public function getTitle()
    {
        return __d('shop', 'Shipping Address');
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        // check if shipping is required
        if (!$this->Checkout->ShopOrders->requiresShipping($this->Checkout->getOrder())) {
            return true;
        }

        // check if shipping address is required
        if (!$this->Checkout->ShopOrders->requiresShippingAddress($this->Checkout->getOrder())) {
            return true;
        }

        if ($this->Checkout->getOrder()->getShippingAddress()) {
            return true;
        }

        // auto-create billing from shipping address
        if ($this->Checkout->getOrder()->getBillingAddress()) {
            $address = $this->Checkout->getOrder()->getBillingAddress();

            /** @var \Shop\Model\Entity\ShopOrderAddress $shippingAddress */
            $shippingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity($address->extractAddress(), ['validate' => false]);
            if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $shippingAddress, 'S')) {
                $this->Checkout->reloadOrder();
            } else {
                $this->log('ShippingAddress: Failed to create shipping address from billing address');
            }
        }

        return false;
    }

    /**
     * @param Controller $controller
     * @return bool|\Cake\Http\Response
     */
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

        if ($controller->getRequest()->is(['put', 'post'])) {
            $op = $controller->getRequest()->getData('_op');
            switch ($op) {
                case "shipping-customer-select":
                    $addressId = $controller->getRequest()->getData('customer_address_id');

                    if ($this->Checkout->ShopOrders->setOrderAddressFromCustomerAddress($this->Checkout->getOrder(), $addressId, 'S')) {
                        $this->Checkout->reloadOrder();
                        $controller->Flash->success(__d('shop', 'Shipping address has been updated'));

                        return true;
                    }
                    break;

                default:
                    /** @var \Shop\Model\Entity\ShopOrderAddress $shippingAddress */
                    $shippingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->patchEntity($shippingAddress, $controller->getRequest()->getData());
                    if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $shippingAddress, 'S')) {
                        $this->Checkout->reloadOrder();
                        $controller->Flash->success(__d('shop', 'Shipping address has been updated'));

                        return true;
                    }
                    break;
            }
        }

        $controller->set('shippingAddress', $shippingAddress);
        $controller->set('shippingAddresses', $this->Checkout->Shop->getCustomerAddressesList());
        $controller->set('countries', $this->Checkout->Shop->getCountriesList());

        return $controller->render('shipping_address');
    }
}
