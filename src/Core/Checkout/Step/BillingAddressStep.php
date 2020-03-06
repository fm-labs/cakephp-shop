<?php
namespace Shop\Core\Checkout\Step;

use Cake\Controller\Controller;
use Cake\Log\Log;
use Cake\Http\Exception\BadRequestException;
use Shop\Core\Checkout\CheckoutStepInterface;

/**
 * Class BillingAddressStep
 *
 * @package Shop\Core\Checkout\Step
 */
class BillingAddressStep extends BaseStep implements CheckoutStepInterface
{

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return __d('shop', 'Billing Address');
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        if ($this->Checkout->getOrder()->getBillingAddress()) {
            return true;
        }

        // auto-create billing from shipping address
        if ($this->Checkout->getOrder()->getShippingAddress()) {
            $address = $this->Checkout->getOrder()->getShippingAddress();

            $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity($address->extractAddress(), ['validate' => false]);
            if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $billingAddress, 'B')) {
                $this->Checkout->reloadOrder();

                return true;
            } else {
                $this->log('Failed to create billing address from shipping address');
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
        if ($this->Checkout->getOrder()->getBillingAddress()) {
            $billingAddress = $this->Checkout->getOrder()->getBillingAddress();
        } elseif ($this->Checkout->Shop->getCustomer()) {
            // prefill with customer data
            $customerData = $this->Checkout->Shop->getCustomer()->extract(['first_name', 'last_name']);
            $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity($customerData, ['validate' => false]);
        } else {
            $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->newEntity();
        }

        if ($controller->getRequest()->is(['put', 'post'])) {
            $op = $controller->getRequest()->data('_op');
            switch ($op) {
                case "billing-customer-select":
                    $addressId = $controller->getRequest()->data('customer_address_id');

                    if ($this->Checkout->ShopOrders->setOrderAddressFromCustomerAddress($this->Checkout->getOrder(), $addressId, 'B')) {
                        $this->Checkout->reloadOrder();
                        $controller->Flash->success(__d('shop', 'Billing information has been updated!'));

                        return true;
                    }
                    break;

                default:
                    $billingAddress = $this->Checkout->ShopOrders->ShopOrderAddresses->patchEntity($billingAddress, $controller->getRequest()->data);
                    if ($this->Checkout->ShopOrders->setOrderAddress($this->Checkout->getOrder(), $billingAddress, 'B')) {
                        $this->Checkout->reloadOrder();
                        $controller->Flash->success(__d('shop', 'Billing information has been updated'));

                        return true;
                    }
                    break;
            }
        }

        $controller->set('billingAddress', $billingAddress);
        $controller->set('billingAddresses', $this->Checkout->Shop->getCustomerAddressesList());
        $controller->set('countries', $this->Checkout->Shop->getCountriesList());

        return $controller->render('billing_address');
    }
}
