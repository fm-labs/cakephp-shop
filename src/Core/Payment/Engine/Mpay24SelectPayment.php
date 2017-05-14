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
use Shop\Model\Entity\ShopOrderTransaction;

class Mpay24SelectPayment implements PaymentEngineInterface
{

    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    public function checkout(CheckoutComponent $Checkout)
    {
        return null;
    }

    protected function _buildMpay24Config()
    {

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

        return $config;
    }

    /**
     * Build the mPAY24 order MDXI
     *
     * @link https://docs.mpay24.com/docs/mdxi-xml
     * @link https://docs.mpay24.com/docs/checkout-payment
     * @link https://docs.mpay24.com/docs/working-with-the-mpay24-php-sdk-redirect-integration
     *
     *
     * @param MPay24Order $mdxi
     * @return MPay24Order
     */
    protected function _buildPaymentMDXI(Mpay24Order $mdxi)
    {
        // override in subclasses

        //$mdxi->Order->PaymentTypes->setEnable("true");
        //$mdxi->Order->PaymentTypes->Payment(1)->setType("EPS");
        //$mdxi->Order->PaymentTypes->Payment(2)->setType("SOFORT");
        //$mdxi->Order->PaymentTypes->Payment(3)->setType("CC");
        return $mdxi;
    }

    public function pay(PaymentComponent $Payment, ShopOrder $order, ShopOrderTransaction $transaction)
    {

        try {

            // Initialize Mpay24
            $config = $this->_buildMpay24Config();
            $mpay24 = new Mpay24($config);

            // @TODO this 'class_exists' call is necessary otherwise PHP throws 'class not found' ?!
            // possibly because this repo is currently overlapping with the legacy source ?!
            // even though we using composer ?!
            if (!class_exists('Mpay24\Mpay24Order')) {
                throw new \RuntimeException('Class Mpay24Order not found');
            }

            // create mpay24 mdxi
            $mdxi = new Mpay24Order();

            //$mdxi->Order->ClientIP = $order->customer_ip;
            $mdxi->Order->UserField = $order->uuid;
            $mdxi->Order->Tid = uniqid() . $transaction->id;

            $mdxi->Order->setStyle("margin-left: auto; margin-right: auto;");
            $mdxi->Order->TemplateSet->setCSSName("MODERN"); // DEFAULT, MOBILE, MODERN
            $mdxi->Order->TemplateSet->setLanguage("DE");


            $mdxi = $this->_buildPaymentMDXI($mdxi);


            $mdxi->Order->ShoppingCart->Description = __d('shop', 'Order {0}', $order->nr_formatted);

            /*
            $mdxi->Order->ShoppingCart->Item(1)->Number = "Item Number 1";
            $mdxi->Order->ShoppingCart->Item(1)->ProductNr = "Product Number 1";
            $mdxi->Order->ShoppingCart->Item(1)->Description = "Description 1";
            $mdxi->Order->ShoppingCart->Item(1)->Package = "Package 1";
            $mdxi->Order->ShoppingCart->Item(1)->Quantity = 2;
            $mdxi->Order->ShoppingCart->Item(1)->ItemPrice = 12.34;
            $mdxi->Order->ShoppingCart->Item(1)->ItemPrice->setTax(1.23);
            $mdxi->Order->ShoppingCart->Item(1)->Price = 24.68;
            */
            $i = 1;
            foreach ($order->shop_order_items as $item) {
                $mdxi->Order->ShoppingCart->Item($i)->Number = (string)$i;
                $mdxi->Order->ShoppingCart->Item($i)->ProductNr = $item->sku;
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


                //@TODO Implement Mpay24 Customer profiles
                // https://docs.mpay24.com/docs/profiles
                //$mdxi->Order->Customer->setUseProfile("false");
                //$mdxi->Order->Customer->setId("98765");
                //$mdxi->Order->Customer = "Hans Mayer";
                // initialize payment
            }

            $mdxi->Order->URL->Success = Router::url($Payment->getSuccessUrl(), true);
            $mdxi->Order->URL->Error = Router::url($Payment->getErrorUrl(), true);
            $mdxi->Order->URL->Confirmation = Router::url($Payment->getConfirmUrl(), true);
            $mdxi->Order->URL->Cancel = Router::url($Payment->getCancelUrl(), true);


            debug($mdxi->toXML());
            if (!$mdxi->validate()) {
                //@TODO Log invalid mdxi xml
                throw new \RuntimeException('Failed to validate MDXI.');
            }

            $mpay24Response = $mpay24->paymentPage($mdxi);
            $paymentPageURL = $mpay24Response->getLocation(); // redirect location to the payment page
            return $Payment->redirect($paymentPageURL);

            // debug
            //$debugInfo = ['mdxi' => $mdxi->toXML(), 'url' => $paymentPageURL];
            //$Payment->getController()->set('debugInfo', $debugInfo);
            //$Payment->getController()->set('paymentUrl', $paymentPageURL);

        } catch(\Exception $ex) {

            if (Configure::read('debug')) {
                throw $ex;
            }

            Log::error('Mpay24::pay: ' . $ex->getMessage(), ['mpay24']);
            throw new \Exception(__d('shop','Payment failed'));
        }
    }

    public function confirm(PaymentComponent $Payment)
    {

    }

    static public function formatPrice($input) {
        return $input;
    }
}