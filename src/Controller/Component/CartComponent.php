<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
use Cake\Controller\Component\CookieComponent;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Table\ShopOrdersTable;
use Shop\Model\Table\ShopProductsTable;

/**
 * Class CartComponent
 * @package Shop\Controller\Component
 *
 * @property ShopOrdersTable $ShopOrders
 * @property ShopProductsTable $ShopProducts
 * @property ShopComponent $Shop
 * @property CookieComponent $Cookie
 */
class CartComponent extends Component
{

    public $components = ['Shop.Shop', 'Cookie'];

    /**
     * @var ShopOrder
     */
    public $order;

    /**
     * @var string
     */
    public $sessionId;

    /**
     * @var string uuid
     */
    public $cartId;

    public function initialize(array $config)
    {
        $this->ShopOrders = TableRegistry::get('Shop.ShopOrders');
        $this->ShopProducts = TableRegistry::get('Shop.ShopProducts');

        /*
        $this->Cookie->configKey('Cart', [
            'path' => '/',
            'expires' => '+10 days',
            'httpOnly' => true,
            'domain' => '*',
            //'encryption' => false
        ]);
        */

        $this->_init();
    }

    public function beforeFilter(Event $event)
    {
        //debug($this->Cookie->read('Cart'));
        //debug($this->request->cookies);
    }

    public function beforeRender(Event $event)
    {
        $this->updateSession();

        $event->subject()->set('order', $this->getOrder());
    }

    public function _init()
    {

        $this->sessionId = $this->request->session()->id();
        $this->cartId = $this->request->session()->check('Shop.Cart.id')
            ? $this->request->session()->read('Shop.Cart.id')
            : Text::uuid();

        //$this->Cookie->write('Cart', $this->cartId);

        $this->order = null;
    }

    public function reset()
    {
        $this->sessionId = null;
        $this->cartId = null;
        $this->order = null;
        $this->resetSession();

        $this->_init();
    }

    public function addItem(array $item)
    {
        $this->_resumeOrder(['create' => true]);

        if (!isset($item['refid'])) {
            throw new \InvalidArgumentException('CartComponent:addItem: RefId missing');
        }

        $item = array_merge([
            'shop_order_id' => $this->order->id,
            'refscope' => 'Shop.ShopProducts',
            'refid' => null,
            'amount' => 1,
        ], $item);


        if ($item['amount'] < 0) {
            $item['amount'] = abs($item['amount']);
        }

        $orderItem = null;
        foreach ($this->order->shop_order_items as $_item) {
            //@TODO also compare order item options
            if ($_item->refscope == $item['refscope'] && $_item->refid == $item['refid']) {
                $orderItem = $_item;
                break;
            }
        }

        if (!$orderItem) {

            $product = $this->ShopProducts->get($item['refid']);
            $item += [
                'title' => $product->title,
                'unit' => ($product->unit) ?: 'x',
                'item_value_net' => $product->price_net,
                'tax_rate' => $product->tax_rate
            ];

            $orderItem = $this->ShopOrders->ShopOrderItems->newEntity($item, ['validate' => true]);

        } elseif ($orderItem && $item['amount'] == 0) {
            return $this->removeItem($orderItem);

        } else {
            $item['amount'] += $orderItem->amount;
            return $this->updateItem($orderItem, $item);
        }

        //$this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.beforeItemAdd', $this, ['item' => $orderItem]));
        $orderItem->calculate();
        if (!$this->ShopOrders->ShopOrderItems->save($orderItem)) {
            debug($orderItem->errors());
            Log::debug('Failed to add order item to order with ID ' . $this->order->id);
            throw new Exception('Failed to add order item to order with ID ' . $this->order->id);
            return false;
        }

        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.afterItemAdd', $this, ['item' => $orderItem]));
        Log::debug('Added order item to order with ID ' . $this->order->id);
        $this->_resumeOrder(['force' => true]);
        return true;
    }


    public function removeItem($orderItem)
    {
        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.beforeItemRemove', $this, ['item' => $orderItem]));

        $success = $this->ShopOrders->ShopOrderItems->delete($orderItem);
        $this->refresh();

        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.afterItemRemove', $this, ['item' => $orderItem]));

        return $success;
    }

    public function removeItemById($orderItemId)
    {
        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);
        return $this->removeItem($orderItem);
    }

    public function updateItem($orderItem, $data = [])
    {
        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.beforeItemUpdate', $this, ['item' => $orderItem]));

        $orderItem->accessible('shop_order_id', false);
        $orderItem->accessible('refscope', false);
        $orderItem->accessible('refid', false);
        $orderItem->accessible('amount', true);
        $orderItem = $this->ShopOrders->ShopOrderItems->patchEntity($orderItem, $data);
        //$orderItem->calculate();
        $success = $this->ShopOrders->ShopOrderItems->save($orderItem);

        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.afterItemUpdate', $this, ['item' => $orderItem]));

        return $success;
    }

    public function updateItemById($orderItemId, $data = [])
    {
        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);
        return $this->updateItem($orderItem, $data);
    }

    public function getOrder()
    {
        $this->_resumeOrder();
        return $this->order;
    }

    public function setOrder(ShopOrder $order, $update = true)
    {
        if (!$order) {
            debug("Warning: setOrder() received empty order");
        }

        $this->order = $order;
        $this->cartId = $order->cartid;

        if ($update === true) {
            $this->saveOrder();
        }
    }

    public function saveOrder()
    {
        if ($this->order) {
            if (!$this->ShopOrders->save($this->order)) {
                throw new \Exception('Failed to save order');
            }
        }

        return $this;
    }

    public function reloadOrder()
    {
        $this->_resumeOrder(['force' => true]);
        return $this;
    }

    public function refresh()
    {
        $this->_resumeOrder();

        if ($this->order) {
            $this->order->calculateItems();
            $this->saveOrder();
        }
    }

    public function getItemsCount()
    {
        if (!$this->order) {
            return false;
        }

        return count($this->order->shop_order_items);
    }

    public function updateSession()
    {
        $this->request->session()->write('Shop.Cart.id', $this->cartId);
        $this->request->session()->write('Shop.Order', $this->getOrder());
    }

    public function resetSession()
    {
        $this->request->session()->delete('Shop.Cart.id');
        $this->request->session()->delete('Shop.Order');
    }

    protected function _createOrder()
    {
        $order = $this->ShopOrders->newEntity([
            'sessionid' => $this->sessionId,
            'cartid' => $this->cartId,
            'is_temporary' => true
        ]);


        if ($this->Shop->getCustomer()) {
            $order->shop_customer_id = $this->Shop->getCustomer()['id'];
        }

        if (!$this->ShopOrders->save($order)) {
            debug($order->errors());
            throw new Exception('Fatal error: Failed to create cart order');
        }
        Log::info("Created cart order with id " . $order->id . " cartId: " . $order->cartid);

        $this->order = $this->ShopOrders
            ->find()
            ->where([
                'ShopOrders.id' => $order->id,
            ])
            ->contain(['ShopOrderItems'])
            ->first();
    }

    protected function _resumeOrder(array $options = [])
    {
        $options += ['create' => false, 'force' => false];

        if (!$this->order || $options['force']) {

            //debug("resuming order with cardid " . $this->cartId);

            $scope = [
                //'sessionid' => $this->sessionId,
                'cartid' => $this->cartId,
                'is_temporary' => true,
            ];

            if ($this->Shop->getCustomer()) {
                $scope['shop_customer_id'] = $this->Shop->getCustomer()['id'];
            }

            $this->order = $this->ShopOrders
                ->find()
                ->where($scope)
                ->contain(['ShopOrderItems'])
                ->first();

            if (!$this->order && $options['create']) {
                $this->_createOrder();
            }

            if ($this->order) {
                $this->order->calculateItems();
            }
        }

        if (!$this->order && ($options['create'] || $options['force'])) {
            Log::error('Resume Order failed for cartId ' . $this->cartId . ' Force: ' . $options['force'] . ' Create: ' . $options['create']);
            throw new NotFoundException('Order not found for cart Id ' . $this->cartId);
        }
    }

}