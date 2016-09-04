<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/8/15
 * Time: 8:42 AM
 */

namespace Shop\Controller;

use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Utility\Text;
use Shop\Lib\LibShopCart;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CartController
 * @package Shop\Controller
 * @property ShopOrdersTable $ShopOrders
 */
class CartController extends AppController
{

    public $modelClass = "Shop.ShopOrders";

    public $cart;

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Banana.Frontend');
        $this->Frontend->setRefScope('Shop.Cart');

        $this->cart = $this->_getCart();
        $this->cart->getOrder();
    }

    public function beforeRender(Event $event)
    {
        $this->set('cart', $this->cart);
        $this->set('cartId', $this->cart->cartId);
        $this->set('sessionId', $this->cart->sessionId);
        $this->set('order', $this->cart->order);
        $this->set('customer', $this->cart->customer);

        $this->_writeCartToSession();
    }

    public function index()
    {
    }

    public function refresh()
    {

        if ($this->cart->refresh()) {
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

        if ($this->cart->addItem($refid, $amount)) {
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
                //$this->cart = $this->_getCart();
                $this->cart->refresh();
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
            if ($this->cart->updateItem($orderItemId, $this->request->data)) {
                $this->Flash->success(__d('shop', 'Updated item'));
            } else {
                $this->Flash->error(__d('shop', 'Failed to update item'));
            }
        }

        $this->redirect($this->referer(['action' => 'index']));
    }

}