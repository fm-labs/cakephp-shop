<?php
declare(strict_types=1);

namespace Shop\Controller;

/**
 * Class CartController
 * @package Shop\Controller
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class CartController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopOrders";

    /**
     * Intialize
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Shop.Cart');
        $this->loadComponent('Shop.Checkout');
        $this->Frontend->setRefScope('Shop.Cart');

        $this->Auth->allow(['index', 'refresh', 'abort', 'add', 'remove', 'update', 'cartUpdate', 'reset']);
    }

    /**
     * Cart index
     */
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

    /**
     * Refresh cart
     */
    public function refresh()
    {
        $result = $this->Cart->refresh();

        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Json');
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

    /**
     * Abort cart order
     */
    public function abort()
    {

        if ($this->Cart->abortOrder()) {
            $this->Flash->success(__d('shop', 'The order has been aborted'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to abort order'));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Add cart item
     */
    public function add()
    {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Json');

            $result = ['success' => false];
            try {
                $this->Cart->addItem($this->request->getData());
                $result['success'] = true;
            } catch (\Exception $ex) {
                $result['error'] = $ex->getMessage();
            }

            $this->set('result', $result);
            $this->set('_serialize', 'result');
        } elseif ($this->request->is(['put', 'post'])) {
            try {
                if (!$this->Cart->addItem($this->request->getData())) {
                    throw new \RuntimeException("Operation failed");
                }
                $this->Flash->success(__d('shop', 'Added item to cart'));
            } catch (\Exception $ex) {
                $this->Flash->error(__d('shop', 'Adding item to cart failed: {0}', $ex->getMessage()));
            }

            $this->autoRender = false;
            $referer = $this->referer(['action' => 'index'], true);
            $this->redirect(['action' => 'index', 'referer' => $referer]);
        }
    }

    /**
     * Remove cart item
     *
     * @param null $orderId
     * @param null $orderItemId
     */
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

    /**
     * Update cart item
     *
     * @param null $orderId
     * @param null $orderItemId
     */
    public function update($orderId = null, $orderItemId = null)
    {

        if ($this->request->is(['post', 'put'])) {
            if ($this->Cart->updateItemById($orderItemId, $this->request->getData())) {
                $this->Flash->success(__d('shop', 'Updated item'));
            } else {
                $this->Flash->error(__d('shop', 'Failed to update item'));
            }
        }

        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Update cart
     */
    public function cartUpdate()
    {
        if (!$this->Cart->getOrder()) {
            $this->Flash->warning(__d('shop', 'Order not found'));
            $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put'])) {
            $order = $this->Cart->getOrder();

            $changed = [];
            foreach ($order->shop_order_items as $item) {
                $amountKey = 'amount_' . $item->id;
                if ($this->request->getData($amountKey)) {
                    $newAmount = $this->request->getData($amountKey);
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

    /**
     * Reset cart
     */
    public function reset()
    {
        if ($this->Cart->reset()) {
            $this->Flash->success(__d('shop', 'The order has been reseted'));
        } else {
            $this->Flash->error(__d('shop', 'Failed to reset order'));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }
}
