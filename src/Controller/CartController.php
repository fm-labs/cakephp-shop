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

        $this->Frontend->setRefScope('Shop.Cart');
    }

    public function index()
    {
    }

    public function refresh()
    {
        if ($this->Cart->refresh()) {
            $this->Flash->success(__d('shop', 'Cart refreshed'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to refresh cart'));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function add($refid = null, $amount = 1)
    {
        if ($this->request->is(['put', 'post'])) {
            $refid = $this->request->data('refid');
            $amount = $this->request->data('amount');
        }

        if ($this->Cart->addItem($refid, $amount)) {
            $this->_writeCartToSession();
            $this->Flash->success(__d('shop', 'Added item to cart'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to add item to cart'));
        }

        $referer = $this->referer(['action' => 'index'], true);
        $this->redirect(['action' => 'index', 'referer' => $referer]);
    }

    public function remove($orderId = null, $orderItemId = null)
    {
        if (!$orderId || !$orderItemId) {
            $this->Flash->error(__d('shop',"Failed to remove item from cart"));
        } else {
            if ($this->ShopOrders->ShopOrderItems->deleteAll([
                'id' => $orderItemId,
                'shop_order_id' => $orderId,
                'refscope' => 'Shop.ShopProducts',
                ])
            ) {
                //$this->Cart = $this->_getCart();
                $this->Cart->refresh();
                $this->_writeCartToSession();

                $this->Flash->success(__d('shop','Item has been removed from cart'));
            } else {
                $this->Flash->error(__d('shop','Failed to remove item from cart'));
            }
        }
        $this->redirect($this->referer());
    }


    public function update($orderId = null, $orderItemId = null)
    {

        if ($this->request->is(['post', 'put'])) {
            if ($this->Cart->updateItem($orderItemId, $this->request->data)) {
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
            $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put'])) {
            $order = $this->Cart->getOrder();

            $changed = [];
            foreach($order->shop_order_items as $item) {
                $amountKey = 'amount_' . $item->id;
                if ($this->request->data($amountKey)) {
                    $newAmount = $this->request->data($amountKey);
                    if ($newAmount != $item->amount) {
                        $this->Cart->updateItem($item->id, ['amount' => $newAmount]);
                        $changed[$item->id] = true;
                    }
                }
            }

            if (count($changed) > 0) {
                $this->Flash->success(__d('shop', '{0} items updated', count($changed)));
                $this->Cart->reloadOrder();
                $this->redirect(['action' => 'index']);
            }
        }


        $this->autoRender = false;
        $this->render('index');
    }
}