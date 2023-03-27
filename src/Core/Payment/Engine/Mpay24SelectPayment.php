<?php
declare(strict_types=1);

namespace Shop\Core\Payment\Engine;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use FmLabs\Mpay24\Lib\Mpay24Client;
use Mpay24\Mpay24;
use Mpay24\Mpay24Config;
use FmLabs\Mpay24\Lib\Mpay24Order;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Controller\Component\PaymentComponent;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Logging\TransactionLoggingTrait;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrderTransactionsTable;

/**
 * Class Mpay24SelectPayment
 *
 * @package Shop\Core\Payment\Engine
 */
class Mpay24SelectPayment implements PaymentEngineInterface
{
    use TransactionLoggingTrait;

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return bool
     */
    public function isCheckoutComplete(CheckoutComponent $Checkout)
    {
        return true;
    }

    /**
     * @param \Shop\Controller\Component\CheckoutComponent $Checkout
     * @return \Cake\Http\Response|null
     */
    public function checkout(CheckoutComponent $Checkout)
    {
        if ($Checkout->getController()->getRequest()->is(['post', 'put'])) {
            $data = $Checkout->getController()->getRequest()->getData();

            $order = $Checkout->getOrder();
            $order->setAccess(['payment_type'], true);
            $order = $Checkout->ShopOrders->patchEntity($order, $data, ['validate' => 'payment']);

            if ($Checkout->setOrder($order, true)) {
                return $Checkout->redirectNext();
            } else {
                debug($order->getErrors());
                $Checkout->getController()->Flash->error("Failed to update payment info");
            }
        } elseif (!$Checkout->getController()->getRequest()->getQuery('change')) {
            return $Checkout->redirectNext();
        }
    }

    /**
     * Build the mPAY24 order MDXI
     *
     * @link https://docs.mpay24.com/docs/mdxi-xml
     * @link https://docs.mpay24.com/docs/checkout-payment
     * @link https://docs.mpay24.com/docs/working-with-the-mpay24-php-sdk-redirect-integration
     *
     *
     * @param \Mpay24\MPay24Order $mdxi
     * @return \Mpay24\MPay24Order
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

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return \Cake\Http\Response|null
     * @throws \Exception
     */
    public function pay(PaymentComponent $Payment, ShopOrderTransaction $transaction, ShopOrder $order)
    {
        try {
            if (!\Cake\Core\Plugin::isLoaded('FmLabs/Mpay24')) {
                throw new \RuntimeException('Mpay24 plugin not loaded');
            }

            /**
             * Use test system with demo user
             */
            $testMode = false;
            if (Configure::read('Shop.Payment.testMode')) {
                $testMode = true;
            }
            //elseif (Configure::read('Mpay24.useTestSystem')) {
            //    $testMode = true;
            //}
            else if (Configure::read('Shop.Demo.enabled') && $order->shop_customer->email == Configure::read('Shop.Demo.username')) {
                $testMode = true;
            }

            $this->logTransaction($transaction, "Mpay24 TestMode: $testMode");

            // @todo Properly initialize Mpay24
            $mpay24 = new Mpay24Client($testMode ? 'testing' : 'production');

            // @TODO Fix: 'class_exists' call is necessary otherwise PHP throws 'class not found' ?!
            if (!class_exists('Mpay24\\Mpay24Order')) {
                throw new \RuntimeException('Class Mpay24Order not found');
            }

            // create mpay24 mdxi
            $mdxi = new Mpay24Order();

            //$mdxi->Order->ClientIP = $order->customer_ip;
            $mdxi->Order->UserField = $order->uuid;
            $mdxi->Order->Tid = $transaction->id;

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

            //debug($mdxi->toXML());
            @file_put_contents(TMP . "payment" . DS . "mpay24_" . $transaction->id . "_" . $order->uuid . "_mdxi.xml", $mdxi->toXML());

            if (!$mdxi->validate()) {
                //@TODO Log invalid mdxi xml
                throw new \RuntimeException('Failed to validate MDXI.');
            }

            $mpay24Response = $mpay24->paymentPage($mdxi);
            @file_put_contents(TMP . "payment" . DS . "mpay24_" . $transaction->id . "_" . $order->uuid . "_response.xml", $mpay24Response->getXml());

            $paymentPageURL = $mpay24Response->getLocation(); // redirect location to the payment page
            $this->logTransaction($transaction, "Mpay24 Payment Page Url: $paymentPageURL");
            if ($paymentPageURL) {
                return $Payment->redirect($paymentPageURL);
                //return $Payment->transactionIframe($transaction->id, $paymentPageURL);
            }

            // debug
            //$debugInfo = ['mdxi' => $mdxi->toXML(), 'url' => $paymentPageURL];
            //$Payment->getController()->set('debugInfo', $debugInfo);
            //$Payment->getController()->set('paymentUrl', $paymentPageURL);
        } catch (\Exception $ex) {
            if (Configure::read('debug')) {
                throw $ex;
            }

            Log::error('Mpay24::pay: ' . $ex->getMessage(), ['mpay24']);
            throw new \Exception(__d('shop', 'Payment failed'));
        }
    }

    /**
     * @param \Shop\Controller\Component\PaymentComponent $Payment
     * @param \Shop\Model\Entity\ShopOrderTransaction $transaction
     * @return \Shop\Model\Entity\ShopOrderTransaction
     */
    public function confirm(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {

        /**
         * ERROR    The transaction failed upon the last request. (e.g. wrong/invalid data, financial reasons, ...)
        RESERVED    The amount was reserved but not settled/billed yet. The transaction was successful.
        RESERVED_REVERSAL   The reserved amount was complaint.
        BILLED  The amount was settled/billed. The transaction was successful.
        BILLED_REVERSAL     The amount was complaint (chargeback). Please get in touch with the customer.
        REVERSED    The reserved amount was released. The transaction was canceled.
        CREDITED    The amount will be refunded. The transaction was credited.
        CREDITED_REVERSAL   The credited amount was complaint.
        SUSPENDED   Expecting external interface confirmation. The transaction is suspended temporarily.
        WITHDRAWN   The payout was successful. The amount will be transfered to the customer.
         */
        $clientIp = $Payment->getController()->getRequest()->clientIp();

        // check ip
        $isTestsystemIp = ($Payment->getController()->getRequest()->clientIp() == '213.208.153.58');

        $query = $Payment->getController()->getRequest()->getQuery(); // + ['OPERATION' => null, 'TID' => null, 'MPAYTID' => null, 'STATUS' => null];
        if ($transaction->id != $query['TID']) { //@TODO Compary hash instead of id
            throw new \RuntimeException('Mpay24Payment::confirm: Transaction Ids do not match');
        }

        if ($query['OPERATION'] == "CONFIRMATION") {
            $transaction->ext_txnid = $query['MPAYTID'];
            $transaction->ext_status = $query['STATUS'];
            $transaction->last_message = $query['OPERATION'] . ":" . $query['STATUS'];
            $transaction->is_test = $isTestsystemIp;

            switch ($query['STATUS']) {
                case "ERROR":
                    $transaction->status = ShopOrderTransactionsTable::STATUS_ERROR;
                    break;
                case "SUSPENDED":
                    $transaction->status = ShopOrderTransactionsTable::STATUS_SUSPENDED;
                    break;
                case "RESERVED":
                    $transaction->status = ShopOrderTransactionsTable::STATUS_RESERVED;
                    break;
                case "BILLED":
                    $transaction->status = ShopOrderTransactionsTable::STATUS_CONFIRMED;
                    break;
                case "RESERVED_REVERSAL":
                case "BILLED_REVERSAL":
                case "CREDITED_REVERSAL":
                    $transaction->status = ShopOrderTransactionsTable::STATUS_REVERSAL;
                    break;
                case "REVERSED":
                case "CREDITED":
                case "WITHDRAWN":
                    $transaction->status = ShopOrderTransactionsTable::STATUS_CREDITED;
                    break;
            }
        }

        return $transaction;
    }

    /**
     * @inheritDoc
     */
    public function cancel(PaymentComponent $Payment, ShopOrderTransaction $transaction)
    {
        return $transaction;
    }

    /**
     * Format price values to mdxi compatible format
     *
     * @param $input
     * @return mixed
     */
    public static function formatPrice($input)
    {
        return $input;
    }
}
