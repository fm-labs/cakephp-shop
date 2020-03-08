<?php

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Component\CookieComponent;
use Cake\Controller\Controller;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Composer\EventDispatcher\EventDispatcher;
use Shop\Core\Product\ShopProductInterface;
use Shop\Event\CartEvent;
use Shop\Model\Entity\ShopOrder;
use Shop\Model\Entity\ShopProduct;
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
    /**
     * @var string
     */
    public static $cookieName = 'Cart';

    /**
     * @var array
     */
    public $components = ['Shop.Shop', 'Flash', 'Cookie'];

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

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->ShopProducts = TableRegistry::getTableLocator()->get('Shop.ShopProducts');

        $this->Cookie->configKey(self::$cookieName, [
            //'path' => '/',
            'expires' => '+99 days',
            'httpOnly' => true,
            //'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'encryption' => true,
        ]);
    }

    /**
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        $this->order = null;
        $this->sessionId = $this->request->getSession()->id();

        // read cart cookies
        $cookie = $this->Cookie->read(self::$cookieName);
        $cookieCartId = ($cookie && isset($cookie['id'])) ? $cookie['id'] : null;

        // read cart session
        $sessionCartId = $this->request->getSession()->read('Shop.Cart.id');

        if ($sessionCartId) { // restore from session
            $this->cartId = $sessionCartId;
        } elseif ($cookieCartId) { // restore from cookie
            debug("cart restored from cookie");
            $this->cartId = $cookieCartId;
        } else { // New cart
            $this->cartId = Text::uuid();
            //debug("write cookie " . $this->cartId);
            //$this->Cookie->write('cartid', $this->cartId);
        }

        // set cookie
        if (!$cookieCartId) {
            //debug("write cookie " . $this->cartId);
            $this->Cookie->write(self::$cookieName . '.id', $this->cartId);
        }

        $this->request->getSession()->write('Shop.Cart.id', $this->cartId);
    }

    /**
     * @param Event $event
     */
    public function beforeRender(Event $event)
    {
        $this->updateSession();
        if ($event->getSubject() instanceof Controller) {
            $event->getSubject()->set('cart', $this->getOrder());
        }
    }

    /**
     * @param Event $event
     */
    public function shutdown(Event $event)
    {
        //@TODO Detach table event listeners
        //@TODO Unload tables
    }

    /**
     * @return bool
     */
    public function reset()
    {
        $this->sessionId = null;
        $this->cartId = null;
        $this->order = null;
        $this->resetSession();

        return true;
    }

    /**
     * @return string
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @param $modelClass
     * @return Table
     */
    protected function _getProductTable($modelClass)
    {
        list(, $modelName) = pluginSplit($modelClass);
        if (!isset($this->{$modelName})) {
            $this->{$modelName} = $this->getController()->loadModel($modelName);
            //if ($this->{$modelName} instanceof EventDispatcher) {
            //    $this->{$modelName}->getEventManager()->on($this);
            //}
        }
        if (!$this->{$modelName}) {
            throw new \RuntimeException("Cart: Failed to load product table: $modelName");
        }

        return $this->{$modelName};
    }

    /**
     * Get product entity with customer discounts applied to net price
     * @param $productId
     * @param string $modelClass
     * @return ShopProductInterface
     */
    public function getProductForCustomer($productId, $modelClass = 'Shop.ShopProducts')
    {
        $product = $this->_getProductTable($modelClass)->get($productId, [
            'for_customer' => $this->Shop->getCustomerId(),
        ]);

        return $product;
    }

    /**
     * Get product entity
     * @param $productId
     * @param string $modelClass
     * @return ShopProductInterface
     */
    public function getProduct($productId, $modelClass = 'Shop.ShopProducts')
    {
        $product = $this->_getProductTable($modelClass)->get($productId);

        return $product;
    }

    /**
     * @param array $item
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function addItem(array $item)
    {
        $this->_resumeOrder(['create' => true]);

        $item = array_merge([
            'shop_order_id' => $this->order->id,
            'refscope' => 'Shop.ShopProducts',
            'refid' => null,
            'amount' => 0,
        ], $item);

        if (!isset($item['refid'])) {
            throw new \InvalidArgumentException('RefId missing');
        }
        if ($item['amount'] < 0) {
            throw new \InvalidArgumentException('Negative value not accepted');
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
            //$product = $this->getProductForCustomer($item['refid'], $item['refscope']);
            $product = $this->getProduct($item['refid'], $item['refscope']);
            $item += [
                'title' => $product->getTitle(),
                'unit' => ($product->getUnit()) ?: 'x', // @deprecated. Redundant information. Can be resolved from product data.
                'item_value_original_net' => $product->getPrice(),
                'item_value_net' => $product->getPrice(),
                'tax_rate' => $product->getTaxRate(),
                'amount' => 1,
            ];

            $orderItem = $this->ShopOrders->ShopOrderItems->newEntity($item, ['validate' => true]);
            $orderItem->calculate();
        } else {
            $item['amount'] += $orderItem->amount;
        }
        $orderItem = $this->updateItem($orderItem, $item);

        $this->getController()->getEventManager()->dispatch(new Event('Shop.Cart.afterItemAdd', $this, [
            'item' => $orderItem,
        ]));
        Log::debug('Added order item to order with ID ' . $this->order->id);

        return $orderItem;
    }

    /**
     * @param $orderItem
     * @param array $data
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function updateItem($orderItem, $data = [])
    {
        if (isset($data['amount']) && $data['amount'] == 0) {
            return $this->removeItem($orderItem);
        }

        $orderItem->accessible('shop_order_id', false);
        $orderItem->accessible('refscope', false);
        $orderItem->accessible('refid', false);

        $event = $this->getController()->getEventManager()->dispatch(new CartEvent('Shop.Cart.beforeItemUpdate', $this, [
            'item' => $orderItem,
            'data' => $data,
            'customer' => $this->Shop->getCustomer(),
        ]));

        $orderItem = $this->ShopOrders->ShopOrderItems->patchEntity($orderItem, $event->getData('data'));
        $orderItem->calculate();
        $success = $this->ShopOrders->ShopOrderItems->save($orderItem);

        $this->reloadOrder();

        $this->getController()->getEventManager()->dispatch(new CartEvent('Shop.Cart.afterItemUpdate', $this, [
            'item' => $orderItem,
            'data' => $data,
            'customer' => $this->Shop->getCustomer(),
        ]));

        return $success;
    }

    /**
     * @param $orderItemId
     * @param array $data
     * @return bool|\Cake\Datasource\EntityInterface|mixed
     */
    public function updateItemById($orderItemId, $data = [])
    {
        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);

        return $this->updateItem($orderItem, $data);
    }

    /**
     * @param $orderItem
     * @return bool|mixed
     */
    public function removeItem($orderItem)
    {
        $this->getController()->getEventManager()->dispatch(new CartEvent('Shop.Cart.beforeItemRemove', $this, ['item' => $orderItem]));

        $success = $this->ShopOrders->ShopOrderItems->delete($orderItem);
        //$this->refresh();
        $this->reloadOrder();

        $this->getController()->getEventManager()->dispatch(new CartEvent('Shop.Cart.afterItemRemove', $this, ['item' => $orderItem]));

        return $success;
    }

    /**
     * @param $orderItemId
     * @return bool|mixed
     */
    public function removeItemById($orderItemId)
    {
        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);

        return $this->removeItem($orderItem);
    }

    /**
     * @return ShopOrder
     */
    public function &getOrder()
    {
        $this->_resumeOrder();

        return $this->order;
    }

    /**
     * @param ShopOrder $order
     * @param bool|true $update
     * @throws \Exception
     */
    public function setOrder(ShopOrder $order, $update = true)
    {
        if (!$order) {
            debug("Warning: setOrder() received empty order");
        }

        $this->order = $order;
        $this->cartId = $order->cartid;

        if ($update === true) {
            $this->saveOrder();
            $this->reloadOrder();
        }
    }

    /**
     * @return bool
     */
    public function abortOrder()
    {
        $this->order = null;
        $this->cartId = null;
        $this->updateSession();

        return true;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function saveOrder()
    {
        if ($this->order) {
            if (!$this->ShopOrders->save($this->order)) {
                throw new \Exception('Failed to save order');
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function reloadOrder()
    {
        if ($this->_order) {
            $this->_resumeOrder(['force' => true]);
        }

        return $this;
    }

    /**
     * @return $this|CartComponent
     * @deprected Use reloadOrder() instead
     */
    public function refresh()
    {
        return $this->reloadOrder();
    }

    /**
     * @return bool|int
     */
    public function getItemsCount()
    {
        if (!$this->order) {
            return false;
        }

        return count($this->order->shop_order_items);
    }

    /**
     * Update session
     */
    public function updateSession()
    {
        $order = null;
        $cart = [
            'id' => $this->cartId,
        ];

        if ($this->getOrder()) {
            $order = $this->getOrder();
            $cart['itemsCount'] = $order->getOrderItemsCount();
            $cart['itemsQty'] = $order->getOrderItemsQty();
        }

        $this->request->getSession()->write('Shop.Cart', $cart);
        //$this->request->getSession()->write('Shop.Order', $order->toArray());
    }

    /**
     * Reset session
     */
    public function resetSession()
    {
        $this->request->getSession()->delete('Shop.Cart');
        $this->request->getSession()->delete('Shop.Order');
    }

    /**
     *
     */
    protected function _createOrder()
    {
        $order = $this->ShopOrders->newEntity([
            'sessionid' => $this->sessionId,
            'cartid' => $this->cartId,
            'is_temporary' => true,
            'shop_customer_id' => $this->Shop->getCustomerId(),
        ]);

        if (!$this->ShopOrders->save($order)) {
            debug($order->getErrors());
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

    /**
     * @param array $options
     */
    protected function _resumeOrder(array $options = [])
    {

        $options += ['create' => false, 'force' => false];

        if (!$this->order || $options['force']) {
            //@TODO check if cart is owned by customer
            $this->order = $this->ShopOrders->find('cart', [
                //'sessionid' => $this->sessionId,
                'cartid' => $this->cartId,
                //'shop_customer_id IS' => $this->Shop->getCustomerId(),
                'is_temporary' => true,
            ]);

            //debug("resuming order with cardid " . $this->cartId);

            /*
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
                ->contain(['ShopOrderItems', 'BillingAddress', 'ShippingAddress'])
                ->first();

            */
            if (!$this->order && $options['create']) {
                $this->_createOrder();
            }
        }

        if (!$this->order && ($options['create'] || $options['force'])) {
            Log::error('Resume Order failed for cartId ' . $this->cartId . ' Force: ' . $options['force'] . ' Create: ' . $options['create']);
            throw new NotFoundException('Order not found for cart Id ' . $this->cartId);
        }
    }
}
