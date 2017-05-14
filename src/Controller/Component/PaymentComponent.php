<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Network\Response;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Core\Payment\PaymentEngineRegistry;
use Shop\Lib\Shop;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopOrderTransaction;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class PaymentComponent
 *
 * @package Shop\Controller\Component
 * @property ShopComponent $Shop
 */
class PaymentComponent extends Component
{

    /**
     * @var array
     */
    public $components = ['Shop.Shop'];

    /**
     * @var ShopOrdersTable
     */
    public $ShopOrders;

    /**
     * List of engine configurations
     * @var array
     */
    public $engines = [];

    /**
     * @var PaymentEngineRegistry
     */
    protected $_engineRegistry;

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'engines' => []
    ];

    /**
     * @var string
     */
    protected $_paymentType;

    /**
     * @var ShopOrder
     */
    protected $_order;

    /**
     * @var ShopOrderTransaction
     */
    protected $_transaction;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        $this->ShopOrders = $this->getController()->loadModel('Shop.ShopOrders');
        $this->_engineRegistry = new PaymentEngineRegistry($this);

        $engines = (isset($config['engines'])) ? $config['engines'] : [];
        $engines = ($engines) ?: (array) Shop::config('Shop.Payment.Engines');

        if (count($engines) < 1) {
            throw new \RuntimeException('Payment: No payment engines configured');
        }

        foreach ($engines as $engine => $config) {
            if (!$this->_engineRegistry->has($engine)) {
                $this->_engineRegistry->load($engine, $config);
            }
        }
        $this->engines = $engines;
    }

    /**
     * @return \Cake\Controller\Controller
     */
    public function getController()
    {
        return $this->_registry->getController();
    }

    /**
     * Checks if payment type is registered in engine registry
     *
     * @param $paymentType
     * @return boolean
     */
    public function accepts($paymentType)
    {
        return $this->_engineRegistry->has($paymentType);
    }

    /**
     * Initialize new ShopOrderTransaction from ShopOrder,
     * load payment engine and execute 'pay' method
     *
     * @param ShopOrder $order
     * @return Response|null
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
            'status' => 0
        ]);

        if (!$this->ShopOrders->ShopOrderTransactions->save($this->_transaction)) {
            debug($this->_transaction->errors());
            throw new \RuntimeException("Payment::initTransaction: Failed to create transaction");
        }

        $response = null;
        try {
            $engine = $this->_engineRegistry->get($order->payment_type);

            // initialize payment with selected payment engine
            $response = $engine->pay($this, $this->_order, $this->_transaction);

            // capture redirect url, if any
            if ($response && $response instanceof Response && $response->location()) {
                $this->_transaction->redirect_url = $response->location();
            }

        } catch (\Exception $ex) {
            // capture errors, if any
            $this->_transaction->message = $ex->getMessage();
            $this->_transaction->status = -1;

            $this->getController()->Flash->error($ex->getMessage());

        } finally {

            if (!$this->ShopOrders->ShopOrderTransactions->save($this->_transaction)) {
                debug($this->_transaction->errors());
                throw new \RuntimeException("Payment::initTransaction: Failed to update transaction");
            }
        }

        return $response;
    }

    /**
     * Convenience wrapper for redirecting
     *
     * @param $url
     * @return Response|null
     */
    public function redirect($url)
    {
        return $this->getController()->redirect($url);
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
        return ['plugin' => 'Shop', 'controller' => 'Payment', 'action' => $action, $this->_order->uuid, 'tid' => $this->_transaction->id];
    }
}