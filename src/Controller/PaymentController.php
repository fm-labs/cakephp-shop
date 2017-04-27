<?php

namespace Shop\Controller;


use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;
use Mpay24\Mpay24;
use Mpay24\Mpay24Config;
use Mpay24\MPay24Order;

class PaymentController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        if ($this->components()->has('Auth')) {
            $this->Auth->allow(['mpay24']);
        }
    }

    public function mpay24($orderId = null) {

        try {

            /*
            $this->loadModel('Shop.ShopOrders');
            $order = $this->ShopOrders->get($orderId);
            if (!$order) {
                throw new NotFoundException();
            }
            */

            $merchantID = '9*****';
            $soapPassword = '******';
            $test = $debug = true;

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
            if(!class_exists('Mpay24\Mpay24Order')) {
                throw new Exception('Class Mpay24Order not found');
            }
            
            // create mpay24 mdxi
            $mdxi = new Mpay24Order();

            $mdxi->Order->setStyle("margin-left: auto; margin-right: auto; width: 600px;");

            $mdxi->Order->UserField = $myTransactionId . 'u';
            $mdxi->Order->Tid = $myTransactionId;

            $mdxi->Order->TemplateSet = "WEB";
            $mdxi->Order->TemplateSet->setLanguage("DE");

            $mdxi->Order->ShoppingCart->Description = "Test Cart";
            /*
            $i = 1;
            foreach($order['OrderItem'] as $item) {
                $mdxi->Order->ShoppingCart->Item($i)->Number = (string) $i;
                $mdxi->Order->ShoppingCart->Item($i)->ProductNr = $item['product_version_id'];
                $mdxi->Order->ShoppingCart->Item($i)->Description = $item['name'];
                $mdxi->Order->ShoppingCart->Item($i)->Quantity = $item['amount'];
                $mdxi->Order->ShoppingCart->Item($i)->Price = self::formatPrice($item['price']);
                $i++;
            }
            $mdxi->Order->ShoppingCart->SubTotal = self::formatPrice($order['Order']['value_items']);
            $mdxi->Order->ShoppingCart->ShippingCosts = self::formatPrice(0);
            $mdxi->Order->ShoppingCart->Discount = self::formatPrice($order['Order']['value_coupon']);
            $mdxi->Order->ShoppingCart->Tax = self::formatPrice($order['Order']['tax']);

            $mdxi->Order->Price = $payment->amount;
            $mdxi->Order->Currency = $payment->currency;

            $mdxi->Order->BillingAddr->setMode('ReadOnly');
            $mdxi->Order->BillingAddr->Name = $order['OrderAddress']['last_name'] . ', ' . $order['OrderAddress']['first_name'];
            $mdxi->Order->BillingAddr->Street = $order['OrderAddress']['address1'];
            $mdxi->Order->BillingAddr->Street2 = $order['OrderAddress']['address2'];
            $mdxi->Order->BillingAddr->Zip = $order['OrderAddress']['zip'];
            $mdxi->Order->BillingAddr->City = $order['OrderAddress']['city'];
            $mdxi->Order->BillingAddr->State = '';
            $mdxi->Order->BillingAddr->Country = $order['OrderAddress']['Country']['iso2'];
            $mdxi->Order->BillingAddr->Email = $order['User']['mail'];
            */


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

            //@TODO Implement Mpay24 Customer profiles
            // https://docs.mpay24.com/docs/profiles
            $mdxi->Order->Customer->setUseProfile("false");
            //$mdxi->Order->Customer->setId("98765");
            //$mdxi->Order->Customer = "Hans Mayer";

            $mdxi->Order->BillingAddr->setMode("ReadWrite"); // or "ReadOnly"
            $mdxi->Order->BillingAddr->Name = "Max Musterman";
            $mdxi->Order->BillingAddr->Street = "Teststreet 1";
            $mdxi->Order->BillingAddr->Street2 = "Teststreet 2";
            $mdxi->Order->BillingAddr->Zip = "1010";
            $mdxi->Order->BillingAddr->City = "Wien";
            $mdxi->Order->BillingAddr->Country->setCode("AT");
            $mdxi->Order->BillingAddr->Email = "a.b@c.de";
            // initialize payment

            $mdxi->Order->URL->Success      = 'http://captaintests.shop/shop/payment/success';
            $mdxi->Order->URL->Error        = 'http://captaintests.shop/shop/payment/error';
            $mdxi->Order->URL->Confirmation = 'http://captaintests.shop/shop/payment/confirmation';

            $paymentPageURL = $mpay24->paymentPage($mdxi)->getLocation(); // redirect location to the payment page

            $this->set('mdxi', $mdxi->toXML());
            $this->set('paymentUrl', $paymentPageURL);

        } catch(\Exception $e) {
            Log::debug('Checkout: ' . $e->getMessage());
            $this->Flash->error($e->getMessage());
        }

    }
    
    static public function formatPrice($input) {
        return $input;
    }
}