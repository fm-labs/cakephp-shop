<?php

namespace Shop\Controller;


use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Mpay24\Mpay24;
use Mpay24\Mpay24Config;
use Mpay24\MPay24Order;

class PaymentController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        $this->Auth->allow(['index', 'mpay24']);
    }

    public function index($orderId = null)
    {
        $this->redirect(['action' => 'mpay24', $orderId]);
    }

    public function mpay24($orderUuid = null) {


        $op = $this->request->query('op');
        $hash = $this->request->query('h');

        switch ($op) {
            case "success":
                Log::debug("mpay24: $op");
                $this->Flash->success(__('Your payment was successful'));
                return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUuid]);
                break;
            case "error":
                Log::debug("mpay24: $op");
                $this->Flash->error(__('The payment has been aborted'));
                return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUuid]);
                break;

            // Push notifications
            case "confirm":
                Log::debug("mpay24: $op");
                Log::debug(json_encode($this->request->data));

                $this->autoRender = false;
                return;
        }


        try {

            /*
            */
            $this->loadModel('Shop.ShopOrders');
            $order = $this->ShopOrders->find('order', ['uuid' => $orderUuid]);
            if (!$order) {
                throw new NotFoundException();
            }

            $merchantID = Configure::read('Mpay24.merchantID'); // '9*****';
            $soapPassword = Configure::read('Mpay24.soapPassword'); //'******';
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

            $mdxi->Order->UserField = $order->uuid;
            $mdxi->Order->Tid = $myTransactionId;

            $mdxi->Order->TemplateSet = "WEB";
            $mdxi->Order->TemplateSet->setLanguage("DE");

            $mdxi->Order->ShoppingCart->Description = __('Order {0}', $order->nr_formatted);
            /*
            */
            $i = 1;
            foreach($order->shop_order_items as $item) {
                $mdxi->Order->ShoppingCart->Item($i)->Number = (string) $i;
                $mdxi->Order->ShoppingCart->Item($i)->ProductNr = $item->refid;
                $mdxi->Order->ShoppingCart->Item($i)->Description = $item->title;
                $mdxi->Order->ShoppingCart->Item($i)->Quantity = $item->amount;
                $mdxi->Order->ShoppingCart->Item($i)->Price = self::formatPrice($item->value_total_taxed);
                $i++;
            }
            //$mdxi->Order->ShoppingCart->SubTotal = self::formatPrice($order->items_value_taxed);
            //$mdxi->Order->ShoppingCart->ShippingCosts = self::formatPrice(0); //@TODO Add shipping costs
            //$mdxi->Order->ShoppingCart->Discount = self::formatPrice(0); //@TODO Add discount value
            //$mdxi->Order->ShoppingCart->Tax = self::formatPrice($order->order_value_tax);

            $mdxi->Order->Price = self::formatPrice($order->order_value_total);
            $mdxi->Order->Currency = $order->currency;

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

            $hash = sha1(Configure::read('Security.salt') . '|' . $orderUuid);
            $successUrl = Router::url(['plugin' => 'Shop', 'controller' => 'Payment', 'action' => 'mpay24', $orderUuid, 'op' => 'success', 'h' => $hash], true);
            $errorUrl = Router::url(['plugin' => 'Shop', 'controller' => 'Payment', 'action' => 'mpay24', $orderUuid, 'op' => 'error', 'h' => $hash], true);
            $confirmUrl = Router::url(['plugin' => 'Shop', 'controller' => 'Payment', 'action' => 'mpay24', $orderUuid, 'op' => 'confirm', 'h' => $hash], true);

            $mdxi->Order->URL->Success      = $successUrl;
            $mdxi->Order->URL->Error        = $errorUrl;
            $mdxi->Order->URL->Confirmation = $confirmUrl;

            $paymentPageURL = null;
            if ($mdxi->validate()) {
                $paymentPageURL = $mpay24->paymentPage($mdxi)->getLocation(); // redirect location to the payment page
            }


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