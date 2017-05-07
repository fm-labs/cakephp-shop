<?php

namespace Shop\Controller;

use Cake\Event\Event;
use Shop\Controller\Component\CartComponent;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CartController
 * @package Shop\Controller
 * @property ShopOrdersTable $ShopOrders
 * @property CartComponent $Cart
 */
class CartController extends AppController
{

    public $modelClass = "Shop.ShopOrders";

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Shop.Cart');
        $this->Frontend->setRefScope('Shop.Cart');

        $this->Auth->allow();
    }

    public function index()
    {
        $order = $this->Cart->getOrder();
        $view = null;

        if (!$order || count($order->shop_order_items) < 1) {
            $view = 'empty';
        }

        $this->autoRender = false;
        $this->render($view);
    }

    public function refresh()
    {
        $result = $this->Cart->refresh();

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->className('Json');
            $this->set('result', ['success' => $result]);
            $this->set('_serialize', 'result');
        }

        if ($result) {
            $this->Flash->success(__d('shop', 'Cart refreshed'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to refresh cart'));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function abort()
    {

        if ($this->Cart->abortOrder()) {
            $this->Flash->success(__d('shop', 'The order has been aborted'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to abort order'));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function add()
    {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->className('Json');

            $result = ['success' => false];
            try {
                $this->Cart->addItem($this->request->data());
                $result['success'] = true;
            } catch (\Exception $ex) {
                $result['error'] = $ex->getMessage();
            }

            $this->set('result', $result);
            $this->set('_serialize', 'result');

        } elseif ($this->request->is(['put', 'post'])) {

            try {
                $this->Cart->addItem($this->request->data());
                $this->Flash->success(__d('shop', 'Added item to cart'));
            } catch (\Exception $ex) {
                $this->Flash->error(__d('shop', 'Adding item to cart failed: {0}', $ex->getMessage()));
            }

            $referer = $this->referer(['action' => 'index'], true);
            $this->redirect(['action' => 'index', 'referer' => $referer]);
        }

    }

    public function remove($orderId = null, $orderItemId = null)
    {
        //@TODO Allow POST only
        if ($this->Cart->removeItemById($orderItemId)) {
            $this->Flash->success(__d('shop', 'Item has been removed from cart'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to remove item from cart'));
        }
        $this->redirect($this->referer());
    }


    public function update($orderId = null, $orderItemId = null)
    {

        if ($this->request->is(['post', 'put'])) {
            if ($this->Cart->updateItemById($orderItemId, $this->request->data)) {
                $this->Flash->success(__d('shop', 'Updated item'));
            } else {
                $this->Flash->error(__d('shop', 'Failed to update item'));
            }
        }

        $this->redirect($this->referer(['action' => 'index']));
    }

    public function cartUpdate()
    {
        if (!$this->Cart->getOrder()) {
            $this->Flash->warning(__d('shop', 'Order not found'));
            $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put'])) {
            debug($this->request->data);
            $order = $this->Cart->getOrder();

            $changed = [];
            foreach($order->shop_order_items as $item) {
                $amountKey = 'amount_' . $item->id;
                if ($this->request->data($amountKey)) {
                    $newAmount = $this->request->data($amountKey);
                    if ($newAmount != $item->amount) {
                        $this->Cart->updateItemById($item->id, ['amount' => $newAmount]);
                        $changed[$item->id] = true;
                    }
                }
            }

            //if (count($changed) > 0) {
                $this->Flash->success(__d('shop', '{0} item(s) updated', count($changed)));
                $this->Cart->reloadOrder();
                $this->redirect(['action' => 'index']);
                return;
            //}
        }


        $this->autoRender = false;
        $this->render('index');
    }
}