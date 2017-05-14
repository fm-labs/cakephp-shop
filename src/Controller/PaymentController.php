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
     * @param null $orderUUID
     * @return \Cake\Network\Response|null
     */
    public function index($orderUUID = null)
    {
        $order = $this->_loadOrder($orderUUID);
        //$this->redirect(['action' => 'pay', $orderUUID]);

        $this->set(compact('order'));
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
            return $this->Payment->initTransaction($order);
            
        } catch (\Exception $ex) {
            $this->Flash->error($ex->getMessage());
        }

    }


    /**
     * Return URL for successful payments
     *
     * @param null $orderUUID
     * @return \Cake\Network\Response|null
     */
    public function success($orderUUID = null)
    {
        Log::debug("Payment::success: $orderUUID", ['shop', 'payment']);
        $this->Flash->success(__d('shop', 'Your payment was successful'));
        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'success']);
    }

    /**
     * Return URL for failed payments
     *
     * @param null $orderUUID
     * @return \Cake\Network\Response|null
     */
    public function error($orderUUID = null)
    {
        Log::error("Payment::error: $orderUUID", ['shop', 'payment']);
        $this->Flash->error(__d('shop', 'The payment could not be completed'));
        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'error']);
    }

    /**
     * Return URL for failed payments
     *
     * @param null $orderUUID
     * @return \Cake\Network\Response|null
     */
    public function cancel($orderUUID = null)
    {
        Log::error("Payment::cancel: $orderUUID", ['shop', 'payment']);
        $this->Flash->error(__d('shop', 'The payment has been canceled'));
        return $this->redirect(['controller' => 'Orders', 'action' => 'view', $orderUUID, 'payment' => 'cancel']);
    }

    /**
     * HTTP confirmation interface for 3rd party payment providers
     *
     * @param null $orderUUID
     */
    public function confirm($orderUUID = null)
    {
        $this->autoRender = false;

        $query = $this->request->query;
        $data = $this->request->data;
        $clientIp = $this->request->clientIp();
        $params = $this->request->params;
        $url = $this->request->url;

        Log::debug(sprintf('Payment::confirm: [%s][%s] %s', (string) $orderUUID, $clientIp, json_encode($params)), ['shop', 'payment']);

        $pack = compact('orderUUID', 'params', 'query', 'data', 'clientIp', 'url');
        $json = json_encode($pack);

        $key = ($orderUUID) ? $orderUUID : 'md5:'.md5($json);
        $key = time() . '_' . $key;

        $path = TMP . 'confirm';
        if (!is_dir($path)) {
            @mkdir($path, 0777);
        }

        $file = $path . DS . $key . '.json';
        $f = new File($file, true);
        $f->write($json);
        $f->close();
    }

}