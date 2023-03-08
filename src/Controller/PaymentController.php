<?php
declare(strict_types=1);

namespace Shop\Controller;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Log\Log;
use Shop\Logging\TransactionLoggingTrait;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class PaymentController
 *
 * Handle shop order payments.
 *
 * @package Shop\Controller
 * @property \Shop\Controller\Component\ShopComponent $Shop
 * @property \Shop\Controller\Component\PaymentComponent $Payment
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
class PaymentController extends AppController
{
    use TransactionLoggingTrait;

    /**
     * @var string
     */
    public $modelClass = "Shop.ShopOrders";

    /**
     * @var \Shop\Model\Entity\ShopOrder
     */
    protected $_order = null;

    /**
     * @param \Cake\Event\Event $event
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->loadComponent('Shop.Shop');
        $this->loadComponent('Shop.Payment');

        //@TODO Add configurable option, to enable/disable authentication on payment controller methods
        $this->Authentication->allowUnauthenticated(['index', 'pay', 'success', 'error', 'confirm']);
    }

    /**
     * @param $orderUUID
     * @return \Shop\Model\Entity\ShopOrder
     */
    protected function _loadOrder(?string $orderUUID = null)
    {
        if (!$orderUUID) {
            throw new BadRequestException();
        }

        if ($this->_order === null || $this->_order->uuid != $orderUUID) {
            $this->loadModel('Shop.ShopOrders');
            $order = $this->ShopOrders
                ->find('order', ['uuid' => $orderUUID])
                ->firstOrFail();

//            if (!$order) {
//                throw new NotFoundException();
//            }
            $this->_order = $order;
        }

        return $this->_order;
    }

    /**
     * @param $txnId
     * @return \Shop\Model\Entity\ShopOrderTransaction
     */
    protected function _loadTransaction(?string $txnId = null)
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
     * @param string|null $orderUUID
     * @return Response|null
     */
    public function index(?string $orderUUID = null)
    {
        $order = $this->_loadOrder($orderUUID);
        //$this->redirect(['action' => 'pay', $orderUUID]);

        $this->set(compact('order'));

        $shopOrder = $this->ShopOrders
            ->find('order', ['ShopOrders.uuid' => $orderUUID])
            ->firstOrFail();

//        if (!$shopOrder) {
//            throw new NotFoundException();
//        }

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
                // continue to payment page
                $redirectUrl = ['action' => 'pay', $orderUUID];
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

        return $this->redirect($redirectUrl);
    }

    /**
     * Initialize payment with selected payment engine
     * Redirects to 3rd party site, if necessary.
     *
     * @param string|null $orderUUID
     * @return void
     */
    public function pay(?string $orderUUID = null)
    {
        $order = $this->_loadOrder($orderUUID);

        $this->set('order', $order);
        $this->set('engines', $this->Payment->engines);
        try {
            Log::info("Payment::initTransaction for order with UUID: $orderUUID", ['shop', 'payment']);
            $this->Payment->initTransaction($order);
        } catch (\Exception $ex) {
            $this->Flash->error($ex->getMessage());
        }
    }

    /**
     * Return URL for successful payments
     *
     * @param string|null $txnId
     * @return Response|null
     */
    public function success(?string $txnId = null)
    {
        $transaction = $this->_loadTransaction($txnId);
        $this->logTransaction($transaction, "Payment::Controller::success", 'info');
        $orderUUID = $transaction->shop_order->uuid;

        $this->Flash->success(__d('shop', 'Your payment was successful'));

        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'success']);
    }

    /**
     * Return URL for failed payments
     *
     * @param string|null $txnId
     * @return Response|null
     */
    public function error(?string $txnId = null)
    {
        $transaction = $this->_loadTransaction($txnId);
        $this->logTransaction($transaction, "Payment::Controller::error", 'error');
        $orderUUID = $transaction->shop_order->uuid;

        $this->Flash->error(__d('shop', 'The payment could not be completed'));

        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'error']);
    }

    /**
     * Return URL for failed payments
     *
     * @param string|null $txnId
     * @return Response|null
     */
    public function cancel(?string $txnId = null)
    {
        $transaction = $this->_loadTransaction($txnId);
        try {
            $this->logTransaction($transaction, "Payment::Controller::cancel", 'warning');
            $orderUUID = $transaction->shop_order->uuid;
            $this->Payment->cancelTransaction($transaction);
        } catch (\Exception $ex) {
            $this->logTransaction($transaction,'Payment::Controller::cancel: ERROR: ' . $ex->getMessage(), 'error');
        }

        $this->Flash->error(__d('shop', 'The payment has been canceled'));

        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'cancel']);
    }

    /**
     * HTTP confirmation interface for 3rd party payment providers
     *
     * @param string|null $txnId
     * @return void|null
     */
    public function confirm(?string $txnId = null)
    {
        $this->autoRender = false;

        // process the request with the appropriate payment engine
        if (!$txnId) {
            Log::critical("Payment::Controller::confirm: No transaction id", ['shop', 'payment']);
            return null;
        }

        $t = $this->_loadTransaction($txnId);
        try {
            $this->logTransaction($t, "Payment::Controller::confirm");
            $this->Payment->confirmTransaction($t);
        } catch (\Exception $ex) {
            $this->logTransaction($t,'Payment::Controller::confirm: ERROR: ' . $ex->getMessage(), 'error');
            $this->response = $this->response->withStatus(400);
        }
    }

    public function external(?string $txnId = null)
    {
        $external = $this->getRequest()->getSession()->read('Shop.Payment.external');
        $this->set(compact('txnId', 'external'));
    }
}
