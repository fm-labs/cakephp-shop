<?php
declare(strict_types=1);

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Filesystem\File;
use Cake\Http\Response;
use Cake\Log\Log;
use Shop\Core\Payment\PaymentEngineRegistry;
use Shop\Lib\Shop;
use Shop\Logging\TransactionLoggingTrait;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrderTransactionsTable;

/**
 * Class PaymentComponent
 *
 * @package Shop\Controller\Component
 * @property \Shop\Controller\Component\ShopComponent $Shop
 */
class PaymentComponent extends Component
{
    use TransactionLoggingTrait;

    /**
     * @var array
     */
    public $components = ['Shop.Shop'];

    /**
     * @var \Shop\Model\Table\ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * List of engine configurations
     * @var array
     */
    public $engines = [];

    /**
     * @var \Shop\Core\Payment\PaymentEngineRegistry
     */
    protected $_engineRegistry;

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'engines' => [],
    ];

    /**
     * @var string
     */
    protected $_paymentType;

    /**
     * @var \Shop\Model\Entity\ShopOrder
     */
    protected $_order;

    /**
     * @var \Shop\Model\Entity\ShopOrderTransaction
     */
    protected $_transaction;

    /**
     * @param array $config
     */
    public function initialize(array $config): void
    {
        $this->ShopOrders = $this->getController()->loadModel('Shop.ShopOrders');
        $this->_engineRegistry = new PaymentEngineRegistry($this);

        $engines = $config['engines'] ?? [];
        $engines = $engines ?: (array)Shop::config('Shop.Payment.Engines');

        if (count($engines) < 1) {
            throw new \RuntimeException('Payment: No payment engines configured');
        }

        foreach ($engines as $engine => $config) {
            if (!$this->_engineRegistry->has($engine)) {
                $this->_engineRegistry->load($engine, $config);
            }
        }
        $this->engines = $engines;

        //$this->getController()->getEventManager()->on(new PaymentListener());
    }

    /**
     * Checks if payment type is registered in engine registry
     *
     * @param $paymentType
     * @return bool
     */
    public function accepts($paymentType)
    {
        return $this->_engineRegistry->has($paymentType);
    }

    /**
     * Initialize new ShopOrderTransaction from ShopOrder,
     * load payment engine and execute 'pay' method
     *
     * @param \Shop\Model\Entity\ShopOrder $order
     * @return \Cake\Http\Response|null
     */
    public function initTransaction(ShopOrder $order)
    {
        if (!$order->payment_type) {
            throw new \RuntimeException('Payment::initTransaction: Order has no payment type');
        }

        if (!$this->_engineRegistry->has($order->payment_type)) {
            throw new \RuntimeException('Missing payment engine: ' . $order->payment_type);
        }

        $this->_paymentType = $order->payment_type;
        $this->_order = $order;

        $this->_transaction = $this->ShopOrders->ShopOrderTransactions->newEntity([
            'shop_order_id' => $order->id,
            'value' => $order->order_value_total,
            'currency_code' => $order->currency,
            'type' => 'P',
            'engine' => $order->payment_type,
            'status' => ShopOrderTransactionsTable::STATUS_INIT,
        ]);

        if (!$this->ShopOrders->ShopOrderTransactions->save($this->_transaction)) {
            debug($this->_transaction->getErrors());
            throw new \RuntimeException("Payment::initTransaction: Failed to create transaction");
        }

        $this->logTransaction($this->_transaction, sprintf(
            "Created new txn with Id %s for order with UUID %s", $this->_transaction->id, $order->uuid));

        $response = null;
        try {
            $engine = $this->_engineRegistry->get($order->payment_type);

            // initialize payment with selected payment engine
            $this->logTransaction($this->_transaction, "INIT");
            $response = $engine->pay($this, $this->_transaction, $this->_order);

            // capture redirect url, if any
            if ($response && $response instanceof Response && $response->getHeaderLine('Location')) {
                $this->_transaction->redirect_url = $response->getHeaderLine('Location');
            }
        } catch (\Exception $ex) {
            // capture errors, if any
            $this->_transaction->message = $ex->getMessage();
            $this->_transaction->status = ShopOrderTransactionsTable::STATUS_INTERNAL_ERROR;
            $this->logTransaction($this->_transaction, "PAY:FAILED: " . $ex->getMessage(), 'error');

            $this->getController()->Flash->error($ex->getMessage());
            //throw $ex;
        } finally {
            if (!$this->ShopOrders->ShopOrderTransactions->save($this->_transaction)) {
                debug($this->_transaction->getErrors());
                throw new \RuntimeException("Payment::initTransaction: Failed to update transaction");
            }
        }

        return $response;
    }

    public function confirmTransaction(ShopOrderTransaction $transaction)
    {

        // store the confirmation request first
        $engine = $transaction->engine;
        $txnId = $transaction->id;
        $clientIp = $this->getController()->getRequest()->clientIp();
        $url = $this->getController()->getRequest()->getUri();
        $query = $this->getController()->getRequest()->getQuery();
        $data = $this->getController()->getRequest()->getData();
        $params = $this->getController()->getRequest()->getServerParams();

        $this->logTransaction($transaction, sprintf('CONFIRM [ClientIp:%s]', $clientIp));

        $request = compact('txnId', 'engine', 'clientIp', 'url', 'query', 'data', 'params');
        $json = json_encode($request);

        // dump to file
        $key = $txnId ? $txnId : 'notxnid';
        $key = 'confirm_' . $engine . '_' . time() . '_' . $key;

        $path = TMP . 'payment';
        if (!is_dir($path)) {
            @mkdir($path, 0777);
        }
        $file = $path . DS . $key . '.json';
        $f = new File($file, true);
        $f->write($json);
        $f->close();

        if (!$transaction->engine) {
            throw new \RuntimeException('Payment::confirmTransction: Transaction has no engine');
        }

        if (!$this->_engineRegistry->has($transaction->engine)) {
            throw new \RuntimeException('Missing payment engine: ' . $transaction->engine);
        }

        // create transaction notification
        $notification = $this->ShopOrders->ShopOrderTransactions->ShopOrderTransactionNotifies->newEntity([
            'shop_order_transaction_id' => $transaction->id,
            'type' => 'C',
            'engine' => $engine,
            'request_ip' => $this->getController()->getRequest()->clientIp(),
            'request_json' => $json,
            'is_valid' => false,
            'is_processed' => false,
        ]);
        if (!$this->ShopOrders->ShopOrderTransactions->ShopOrderTransactionNotifies->save($notification)) {
            debug($notification->getErrors());
            $this->logTransaction($transaction, "Failed to save transaction notification", 'error');
        }

        try {
            // Dispatch Shop.Payment.beforeConfirm event
            $this->getController()->dispatchEvent('Shop.Payment.beforeConfirm', [
                'transaction' => $transaction,
                'request' => $this->getController()->getRequest(),
            ], $this);

            $engine = $this->_engineRegistry->get($transaction->engine);
            $transaction = $engine->confirm($this, $transaction);

            // Dispatch Shop.Payment.afterConfirm event
            $this->getController()->dispatchEvent('Shop.Payment.afterConfirm', [
                'transaction' => $transaction,
                'request' => $this->getController()->getRequest(),
            ], $this);
        } catch (\Exception $ex) {
            $this->logTransaction($transaction, sprintf("CONFIRM:FAILED: %s", $ex->getMessage()));
            throw $ex;
        }

        if (!($transaction instanceof ShopOrderTransaction)) {
            throw new \RuntimeException("Payment::confirmTransaction: Response of payment engine MUST be an instance of ShopOrderTransaction");
        }

        if ($transaction->isDirty() && !$this->ShopOrders->ShopOrderTransactions->save($transaction)) {
            debug($transaction->getErrors());
            throw new \RuntimeException("Payment::confirmTransaction: Failed to update transaction");
        }

        $notification->is_valid = true;
        $notification->is_processed = ($transaction->status == 1);
        if (!$this->ShopOrders->ShopOrderTransactions->ShopOrderTransactionNotifies->save($notification)) {
            debug($notification->getErrors());
            $this->logTransaction($transaction, "Failed to update transaction notification", 'error');
        }

        return $transaction;
    }
    
    public function cancelTransaction(ShopOrderTransaction $transaction) {
        try {
            // Dispatch Shop.Payment.beforeCancel event
            $this->getController()->dispatchEvent('Shop.Payment.beforeCancel', [
                'transaction' => $transaction,
                'request' => $this->getController()->getRequest(),
            ], $this);

            $engine = $this->_engineRegistry->get($transaction->engine);

            $this->logTransaction($transaction, "CANCEL");
            $transaction->status = ShopOrderTransactionsTable::STATUS_USER_ABORT;
            $transaction = $engine->cancel($this, $transaction);
            $this->logTransaction($transaction, "CANCELED");

            if (!($transaction instanceof ShopOrderTransaction)) {
                throw new \RuntimeException("Response of payment engine MUST be an instance of ShopOrderTransaction");
            }

            if ($transaction->isDirty() && !$this->ShopOrders->ShopOrderTransactions->save($transaction)) {
                debug($transaction->getErrors());
                throw new \RuntimeException("Failed to update transaction");
            }

            // Dispatch Shop.Payment.afterCancel event
            $this->getController()->dispatchEvent('Shop.Payment.afterCancel', [
                'transaction' => $transaction,
                'request' => $this->getController()->getRequest(),
            ], $this);
        } catch (\Exception $ex) {
            $this->logTransaction($transaction, $ex->getMessage(), 'error');
            throw $ex;
        }
    }

    /**
     * Convenience wrapper for redirecting
     *
     * @param $url
     * @return \Cake\Http\Response|null
     */
    public function redirect($url)
    {
        return $this->getController()->redirect($url);
    }

    public function transactionIframe($txnId, $url)
    {
        $this->getController()->getRequest()->getSession()->write('Shop.Payment.external', [
            'txnId' => $txnId,
            'url' => $url
        ]);
        return $this->getController()->redirect(['plugin' => 'Shop', 'controller' => 'Payment', 'action' => 'external', $txnId]);
    }

    /**
     * @return array
     */
    public function getOrderUrl()
    {
        if (!$this->_order) {
            throw new \LogicException('Can not get order url: No order initialized');
        }

        return ['plugin' => 'Shop', 'controller' => 'Order', 'action' => 'view', $this->_order->uuid];
    }

    /**
     * @return array
     */
    public function getSuccessUrl()
    {
        return $this->_getPaymentUrl('success');
    }

    /**
     * @return array
     */
    public function getErrorUrl()
    {
        return $this->_getPaymentUrl('error');
    }

    /**
     * @return array
     */
    public function getCancelUrl()
    {
        return $this->_getPaymentUrl('cancel');
    }

    /**
     * @return array
     */
    public function getConfirmUrl()
    {
        return $this->_getPaymentUrl('confirm');
    }

    /**
     * @param $action
     * @return array
     */
    protected function _getPaymentUrl($action)
    {
        if (!$this->_order) {
            throw new \LogicException('Can not get payment url: No order initialized');
        }
        if (!$this->_transaction) {
            throw new \LogicException('Can not get payment url: No transaction initialized');
        }

        return ['plugin' => 'Shop', 'controller' => 'Payment', 'action' => $action, $this->_transaction->id,  'o' => $this->_order->uuid];
    }

}
