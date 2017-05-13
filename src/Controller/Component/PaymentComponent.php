<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Network\Response;
use Shop\Core\Payment\PaymentEngineInterface;
use Shop\Core\Payment\PaymentEngineRegistry;
use Shop\Lib\Shop;
use Shop\Model\Entity\ShopOrder;
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
     * @var ShopOrder
     */
    protected $_order;

    /**
     * @var string
     */
    protected $_paymentType;

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
     * @param ShopOrder $order
     * @return mixed
     */
    public function initPayment(ShopOrder $order)
    {
        if (!$order->payment_type) {
            throw new \RuntimeException('initPayment: Order has no payment type');
        }

        if (!$this->_engineRegistry->has($order->payment_type)) {
            throw new \RuntimeException('Missing payment engine: ' . $order->payment_type);
        }

        $this->_paymentType = $order->payment_type;
        $this->_order = $order;

        return $this->_engineRegistry->get($order->payment_type)->pay($this, $this->_order);
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
            throw new \LogicException('Can not get order url: No payment initialized');
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
            throw new \LogicException('Can not get payment url: No payment initialized');
        }
        return ['plugin' => 'Shop', 'controller' => 'Payment', 'action' => $action, $this->_order->uuid];
    }
}