<?php

namespace Shop\Core\Payment\Engine;


use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use Mpay24\Mpay24;
use Mpay24\Mpay24Config;
use Mpay24\MPay24Order;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Model\Entity\ShopOrder;

class Mpay24Payment implements PaymentEngineInterface
{

    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->request->is(['post', 'put'])) {
            $data = $Checkout->request->data();

            $order = $Checkout->getOrder();
            $order->payment_type = 'mpay24';

            if ($order->errors()) {
                throw new \InvalidArgumentException('Please fill all the required fields');
            }

            if ($Checkout->ShopOrders->saveOrder($order)) {
                $Checkout->setOrder($order);
                $Checkout->redirectNext();
            }
        }
    }

    public function pay(PaymentComponent $Payment, ShopOrder $order)
    {
        $orderUuid = $order->uuid;

        try {

            $merchantID = Configure::read('Mpay24.merchantID');
            $soapPassword = Configure::read('Mpay24.soapPassword');
            $test = (bool)Configure::read('Mpay24.test');
            $debug = (bool)Configure::read('Mpay24.debug');

            $config = new Mpay24Config();
            $config->setMerchantID($merchantID);
            $config->setSoapPassword($soapPassword);
            $config->useTestSystem($test);
            $config->setDebug($debug);
            //$config->setProxyHost($proxyHost);
            //$config->setProxyPort($proxyPort);
            //$config->setProxyHost($proxyUser);
            //$config->setProxyPass($proxyPass);
            //$config->setVerifyPeer($verifyPeer);
            $config->setEnableCurlLog($debug);
            $config->setLogFile('mpay24.log');
            $config->setLogPath(LOGS);
            $config->setCurlLogFile('mpay24_curl.log');


            // FLEX
            //$config->setSpid($spid);
            //$config->setFlexLinkPassword($flexLinkPassword);
            //$config->useFlexLinkTestSystem($flexLinkTestSystem);

            // Initialize Mpay24
            $mpay24 = new Mpay24($config);


            $myTransactionId = uniqid('test');

            // @TODO this 'class_exists' call is necessary otherwise PHP throws 'class not found' ?!
            // possibly because this repo is currently overlapping with the legacy source ?!
            // even though we using composer ?!
            if (!class_exists('Mpay24\Mpay24Order')) {
                throw new \RuntimeException('Class Mpay24Order not found');
            }

            // create mpay24 mdxi
            $mdxi = new Mpay24Order();

            $mdxi->Order->setStyle("margin-left: auto; margin-right: auto; width: 600px;");


            $mdxi->Order->UserField = $order->uuid;
            $mdxi->Order->Tid = $myTransactionId;

            $mdxi->Order->TemplateSet = "WEB";
            $mdxi->Order->TemplateSet->setCSSName("MODERN");
            $mdxi->Order->TemplateSet->setLanguage("DE");

            //$mdxi->Order->PaymentTypes->setEnable("true");
            //$mdxi->Order->PaymentTypes->Payment(1)->setType("EPS");
            //$mdxi->Order->PaymentTypes->Payment(1)->setType("SOFORT");
            //$mdxi->Order->PaymentTypes->Payment(3)->setType("CC");

            $mdxi->Order->ShoppingCart->Description = __d('shop', 'Order {0}', $order->nr_formatted);
            /*
            */
            $i = 1;
            foreach ($order->shop_order_items as $item) {
                $mdxi->Order->ShoppingCart->Item($i)->Number = (string)$i;
                $mdxi->Order->ShoppingCart->Item($i)->ProductNr = $item->refid;
                $mdxi->Order->ShoppingCart->Item($i)->Description = $item->title;
                $mdxi->Order->ShoppingCart->Item($i)->Quantity = $item->amount;
                $mdxi->Order->ShoppingCart->Item($i)->Price = self::formatPrice($item->value_total);
                $i++;
            }
            //$mdxi->Order->ShoppingCart->SubTotal = self::formatPrice($order->items_value_taxed);
            //$mdxi->Order->ShoppingCart->ShippingCosts = self::formatPrice(0); //@TODO Add shipping costs
            //$mdxi->Order->ShoppingCart->Discount = self::formatPrice(0); //@TODO Add discount value
            //$mdxi->Order->ShoppingCart->Tax = self::formatPrice($order->order_value_tax);

            $mdxi->Order->Price = self::formatPrice($order->order_value_total);
            $mdxi->Order->Currency = $order->currency;


            // Set personal data only if user is logged in
            if ($Payment->Shop->getCustomerId() == $order->shop_customer_id) {

                $billingAddress = $order->getBillingAddress();
                $mdxi->Order->BillingAddr->setMode("ReadWrite"); // or "ReadOnly"
                $mdxi->Order->BillingAddr->Name = $billingAddress->name;
                $mdxi->Order->BillingAddr->Street = $billingAddress->street;
                $mdxi->Order->BillingAddr->Street2 = $billingAddress->street2;
                $mdxi->Order->BillingAddr->Zip = $billingAddress->zipcode;
                $mdxi->Order->BillingAddr->City = $billingAddress->city;
                $mdxi->Order->BillingAddr->State = '';
                $mdxi->Order->BillingAddr->Country = $billingAddress->relcountry->name;
                $mdxi->Order->BillingAddr->Email = $order->shop_customer->email;

            }



            /*
            $mdxi->Order->ShoppingCart->Item(1)->Number = "Item Number 1";
            $mdxi->Order->ShoppingCart->Item(1)->ProductNr = "Product Number 1";
            $mdxi->Order->ShoppingCart->Item(1)->Description = "Description 1";
            $mdxi->Order->ShoppingCart->Item(1)->Package = "Package 1";
            $mdxi->Order->ShoppingCart->Item(1)->Quantity = 2;
            $mdxi->Order->ShoppingCart->Item(1)->ItemPrice = 12.34;
            $mdxi->Order->ShoppingCart->Item(1)->ItemPrice->setTax(1.23);
            $mdxi->Order->ShoppingCart->Item(1)->Price = 24.68;

            $mdxi->Order->ShoppingCart->Item(2)->Number = "Item Number 2";
            $mdxi->Order->ShoppingCart->Item(2)->ProductNr = "Product Number 2";
            $mdxi->Order->ShoppingCart->Item(2)->Description = "Description 2";
            $mdxi->Order->ShoppingCart->Item(2)->Package = "Package 2";
            $mdxi->Order->ShoppingCart->Item(2)->Quantity = 1;
            $mdxi->Order->ShoppingCart->Item(2)->ItemPrice = 5.67;
            $mdxi->Order->ShoppingCart->Item(2)->Price = 5.67;

            $mdxi->Order->Price = 30.35;

            $mdxi->Order->Currency = "EUR";

            $mdxi->Order->BillingAddr->setMode("ReadWrite"); // or "ReadOnly"
            $mdxi->Order->BillingAddr->Name = "Max Musterman";
            $mdxi->Order->BillingAddr->Street = "Teststreet 1";
            $mdxi->Order->BillingAddr->Street2 = "Teststreet 2";
            $mdxi->Order->BillingAddr->Zip = "1010";
            $mdxi->Order->BillingAddr->City = "Wien";
            $mdxi->Order->BillingAddr->Country->setCode("AT");
            $mdxi->Order->BillingAddr->Email = "a.b@c.de";
            */

            //@TODO Implement Mpay24 Customer profiles
            // https://docs.mpay24.com/docs/profiles
            //$mdxi->Order->Customer->setUseProfile("false");
            //$mdxi->Order->Customer->setId("98765");
            //$mdxi->Order->Customer = "Hans Mayer";
            // initialize payment

            $mdxi->Order->URL->Success = Router::url($Payment->getSuccessUrl(), true);
            $mdxi->Order->URL->Error = Router::url($Payment->getErrorUrl(), true);
            $mdxi->Order->URL->Confirmation = Router::url($Payment->getConfirmUrl(), true);

            // validate mdxi and request payment url
            if (!$mdxi->validate()) {
                //@TODO Log invalid mdxi xml
                throw new \RuntimeException('Mpay24::pay: Failed to validate MDXI.');
            }
            $paymentPageURL = $mpay24->paymentPage($mdxi)->getLocation(); // redirect location to the payment page
            //$paymentPageURL = $mpay24->selectPayment($mdxi)->location; // redirect location to the payment page

            $Payment->redirect($paymentPageURL);

            // debug
            $debugInfo = ['mdxi' => $mdxi->toXML(), 'url' => $paymentPageURL];
            $Payment->getController()->set('debugInfo', $debugInfo);
            //$Payment->getController()->set('paymentUrl', $paymentPageURL);

        } catch(\Exception $ex) {
            Log::error('Mpay24::pay: ' . $ex->getMessage(), ['mpay24']);

            if (Configure::read('debug')) {
                throw $ex;
            }

            throw new \Exception(__('Payment failed'));
        }
    }

    static public function formatPrice($input) {
        return $input;
    }
}