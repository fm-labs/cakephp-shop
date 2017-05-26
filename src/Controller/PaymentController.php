<?php

namespace Shop\Controller;


use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Log\Log;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Shop\Controller\Component\PaymentComponent;
use Shop\Controller\Component\ShopComponent;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class PaymentController
 *
 * Handle shop order payments.
 *
 * @package Shop\Controller
 * @property ShopComponent $Shop
 * @property PaymentComponent $Payment
 * @property ShopOrdersTable $ShopOrders
 */
class PaymentController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopOrders";

    /**
     * @var ShopOrder
     */
    protected $_order = null;

    /**
     * @param Event $event
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadComponent('Shop.Shop');
        $this->loadComponent('Shop.Payment');

        //@TODO Add configurable option, to enable/disable authentication on payment controller methods
        $this->Auth->allow(['index', 'pay', 'success', 'error', 'confirm']);
    }

    /**
     * @param $orderUUID
     * @return ShopOrder
     */
    protected function _loadOrder($orderUUID = null)
    {
        if (!$orderUUID) {
            throw new BadRequestException();
        }

        if ($this->_order === null || $this->_order->uuid != $orderUUID) {
            $this->loadModel('Shop.ShopOrders');
            $order = $this->ShopOrders->find('order', ['uuid' => $orderUUID]);
            if (!$order) {
                throw new NotFoundException();
            }
            $this->_order = $order;
        }
        return $this->_order;
    }

    /**
     * @param $txnId
     * @return ShopOrderTransaction
     */
    protected function _loadTransaction($txnId = null)
    {
        if (!$txnId) {
            throw new BadRequestException();
        }
        $this->loadModel('Shop.ShopOrderTransactions');
        return $this->_transaction = $this->ShopOrderTransactions->find()
            ->where(['ShopOrderTransactions.id' => $txnId])
            ->contain(['ShopOrders'])
            ->firstOrFail();

    }


    /**
     * @param null $orderUUID
     * @return \Cake\Network\Response|null
     */
    public function index($orderUUID = null)
    {
        $order = $this->_loadOrder($orderUUID);
        //$this->redirect(['action' => 'pay', $orderUUID]);

        $this->set(compact('order'));

        $shopOrder = $this->ShopOrders->find('order', ['ShopOrders.uuid' => $orderUUID]);
        if (!$shopOrder) {
            throw new NotFoundException();
        }

        $redirectUrl = ['controller' => 'Orders', 'action' => 'view', $orderUUID];
        switch ($shopOrder->status) {
            case ShopOrdersTable::ORDER_STATUS_TEMP:
                $this->Flash->error(__d('shop', 'The order is temporary'));
                break;

            case ShopOrdersTable::ORDER_STATUS_STORNO:
                $this->Flash->error(__d('shop', 'The order has been canceled'));
                break;

            case ShopOrdersTable::ORDER_STATUS_CLOSED:
                $this->Flash->success(__d('shop', 'The order is already closed'));
                break;

            case ShopOrdersTable::ORDER_STATUS_SUBMITTED:
            case ShopOrdersTable::ORDER_STATUS_PENDING:

                // continue to paymant
                return $this->setAction('pay', $orderUUID);
                break;

            case ShopOrdersTable::ORDER_STATUS_CONFIRMED:
            case ShopOrdersTable::ORDER_STATUS_PAYED:
            case ShopOrdersTable::ORDER_STATUS_DELIVERED:
            case ShopOrdersTable::ORDER_STATUS_ERROR_DELIVERY:
            case ShopOrdersTable::ORDER_STATUS_ERROR:
            default:
                $this->Flash->success(__d('shop', 'We are processing your order'));
                break;
        }

        die("REdirect" . $redirectUrl);
        //return $this->redirect($redirectUrl);
    }

    /**
     * Initialize payment with selected payment engine
     * Redirects to 3rd party site, if necessary.
     *
     * @param null $orderUUID
     * @return \Cake\Network\Response|null
     */
    public function pay($orderUUID = null)
    {
        $order = $this->_loadOrder($orderUUID);

        $this->set('order', $order);
        $this->set('engines', $this->Payment->engines);
        try {
            Log::debug("Payment::initTransaction: $orderUUID", ['shop', 'payment']);
            $this->Payment->initTransaction($order);
            
        } catch (\Exception $ex) {
            $this->Flash->error($ex->getMessage());
        }

    }


    /**
     * Return URL for successful payments
     *
     * @param null $txnId
     * @return \Cake\Network\Response|null
     */
    public function success($txnId = null)
    {
        Log::debug("Payment::success: $txnId", ['shop', 'payment']);

        $transaction = $this->_loadTransaction($txnId);
        $orderUUID = $transaction->shop_order->uuid;

        $this->Flash->success(__d('shop', 'Your payment was successful'));
        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'success']);
    }

    /**
     * Return URL for failed payments
     *
     * @param null $txnId
     * @return \Cake\Network\Response|null
     */
    public function error($txnId = null)
    {
        Log::error("Payment::error: $txnId", ['shop', 'payment']);

        $transaction = $this->_loadTransaction($txnId);
        $orderUUID = $transaction->shop_order->uuid;

        $this->Flash->error(__d('shop', 'The payment could not be completed'));
        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'error']);
    }

    /**
     * Return URL for failed payments
     *
     * @param null $txnId
     * @return \Cake\Network\Response|null
     */
    public function cancel($txnId = null)
    {
        Log::error("Payment::cancel: $txnId", ['shop', 'payment']);

        $transaction = $this->_loadTransaction($txnId);
        $orderUUID = $transaction->shop_order->uuid;

        $this->Flash->error(__d('shop', 'The payment has been canceled'));
        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'cancel']);
    }

    /**
     * HTTP confirmation interface for 3rd party payment providers
     *
     * @param null $txnId
     * @return null|ShopOrderTransaction
     */
    public function confirm($txnId = null)
    {
        $this->autoRender = false;


        // process the request with the appropriate payment engine
        if (!$txnId) {
            Log::warning("Payment::confirm: No transaction id");
            return null;
        }


        try {
            Log::debug("Payment::confirm: $txnId", ['shop', 'payment']);
            $t = $this->_loadTransaction($txnId);
            $this->Payment->confirmTransaction($t);

        } catch (\Exception $ex) {
            Log::debug("Payment::error: " . $ex->getMessage(), ['shop', 'payment']);
            debug($ex->getMessage());
            $this->response->statusCode(400);
        }

    }

}