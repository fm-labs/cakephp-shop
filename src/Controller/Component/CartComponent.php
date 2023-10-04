<?php
declare(strict_types=1);

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Shop\Event\CartEvent;
use Shop\Model\Entity\ShopOrder;

/**
 * Class CartComponent
 * @package Shop\Controller\Component
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Shop\Model\Table\ShopProductsTable $ShopProducts
 * @property \Shop\Controller\Component\ShopComponent $Shop
 */
class CartComponent extends Component
{
    /**
     * @var string
     */
    public static string $cookieName = 'Cart';

    /**
     * @var array
     */
    public $components = ['Shop.Shop', 'Flash', 'Cookie'];

    /**
     * @var \Shop\Model\Entity\ShopOrder|null
     */
    public ?ShopOrder $order = null;

    /**
     * @var string|null
     */
    public ?string $sessionId = null;

    /**
     * @var string|null Cart uuid
     */
    public ?string $cartId;

    /**
     * @param array $config
     */
    public function initialize(array $config): void
    {
        $this->ShopOrders = TableRegistry::getTableLocator()->get('Shop.ShopOrders');
        $this->ShopProducts = TableRegistry::getTableLocator()->get('Shop.ShopProducts');

        //@TODO Migrate shop cookie to cakephp 4.0
        /*
        $this->Cookie->configKey(self::$cookieName, [
            //'path' => '/',
            'expires' => '+99 days',
            'httpOnly' => true,
            //'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'encryption' => true,
        ]);
        */
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->order = null;
        $this->sessionId = $this->getController()->getRequest()->getSession()->id();
        //$this->Flash->info("SessionId: " . $this->sessionId);
        $this->restore();
    }

    protected function restore()
    {
        $cartId = null;

        // restore from session
        $sessionCartId = $this->getController()->getRequest()->getSession()->read('Shop.Cart.id');
        if ($sessionCartId) {
            //$this->Flash->info("SessionCartId: " . $sessionCartId);
            $cartId = $sessionCartId;
        }

        if (!$cartId) {
            $cartId = Text::uuid();
        }

        // @todo restore from cookie

        $this->cartId = $cartId;
        $this->getController()->getRequest()->getSession()->write('Shop.Cart.id', $this->cartId);

        $this->writeCookie();
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        $this->updateSession();
        if ($event->getSubject() instanceof Controller) {
            $event->getSubject()->set('cart', $this->getOrder());
        }
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function afterFilter(\Cake\Event\EventInterface $event)
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
     * @return \Cake\ORM\Table
     */
    protected function _getProductTable($modelClass)
    {
        [, $modelName] = pluginSplit($modelClass);
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
     * @return \Shop\Core\Product\ShopProductInterface|\Shop\Model\Entity\ShopProduct
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
     * @return \Shop\Core\Product\ShopProductInterface|\Shop\Model\Entity\ShopProduct
     */
    public function getProduct($productId, $modelClass = 'Shop.ShopProducts')
    {
        //$product = $this->_getProductTable($modelClass)->get($productId);

        $shopProduct = $this->_getProductTable($modelClass)->get($productId, [
            'contain' => ['ParentShopProducts'],
            'media' => true,
            'for_customer' => $this->Shop->getCustomerId(),
        ]);

        return $shopProduct;
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
                'unit' => $product->getUnit() ?: 'x', // @todo getUnit() MUST return a unit string
                'item_value_original_net' => $product->price_net_original ?? $product->getPrice(),
                'item_value_net' => $product->getPrice(),
                'tax_rate' => $product->getTaxRate(),
                'amount' => 1,
            ];

            $orderItem = $this->ShopOrders->ShopOrderItems->newEntity($item, ['validate' => true]);
            $orderItem->calculate();
        } else {
            $item['amount'] += $orderItem->amount;
        }

//        $this->getController()->getEventManager()->dispatch(new Event('Shop.Cart.beforeItemAdd', $this, [
//            'item' => $orderItem,
//        ]));

        $orderItem = $this->updateItem($orderItem, $item);

        $this->getController()->getEventManager()->dispatch(new Event('Shop.Cart.afterItemAdd', $this, [
            'item' => $orderItem,
        ]));
        Log::debug('Added order item to order with ID ' . $this->order->id, ['shop']);

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

        $orderItem->setAccess('shop_order_id', false);
        $orderItem->setAccess('refscope', false);
        $orderItem->setAccess('refid', false);

        $event = $this->getController()->getEventManager()->dispatch(new CartEvent('Shop.Cart.beforeItemUpdate', $this, [
            'item' => $orderItem,
            'data' => $data,
            'customer' => $this->Shop->getCustomer(),
        ]));
        if ($event->getResult() === false) {
            return false;
        }

        $orderItem = $this->ShopOrders->ShopOrderItems->patchEntity(
            $orderItem,
            $event->getData('data'),
            ['fields' => ['amount']]
        );
        $orderItem->calculate();
        $success = $this->ShopOrders->ShopOrderItems->save($orderItem);
        if (!$success) {
            Log::warning("Failed to save order item: " . json_encode($orderItem->getErrors()), ['shop']);
        }

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
     * @return bool
     */
    public function removeItem($orderItem): bool
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
     * @return bool
     */
    public function removeItemById($orderItemId): bool
    {
        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);

        return $this->removeItem($orderItem);
    }

    /**
     * @return \Shop\Model\Entity\ShopOrder
     */
    public function getOrder(): ?ShopOrder
    {
        $this->_resumeOrder();

        return $this->order;
    }

    /**
     * @param \Shop\Model\Entity\ShopOrder $order
     * @param bool|true $update
     * @throws \Exception
     */
    public function setOrder(ShopOrder $order, $update = true)
    {
        if (!$order) {
            throw new \LogicException("Warning: setOrder() received empty order");
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
     * @return $this|\Shop\Controller\Component\CartComponent
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
        $cart = [
            'id' => $this->cartId,
        ];

        if ($this->getOrder()) {
            $order = $this->getOrder();
            $cart['itemsCount'] = $order->getOrderItemsCount();
            $cart['itemsQty'] = $order->getOrderItemsQty();
        }

        $this->getController()->getRequest()->getSession()->write('Shop.Cart', $cart);

        $this->writeCookie();
    }

    protected function writeCookie()
    {
        //$cookies = $this->getController()->getRequest()->getCookieCollection();
        //$shopCookie = $cookies->has('shop') ? $cookies->get('shop') : null;

        $cookieData = [
            'cartid' => $this->cartId,
            'sessionid' => $this->sessionId,
            'shop_customer_id' => $this->Shop->getCustomerId(),
        ];

        $shopCookie = new Cookie(
            '_shpct', // name
            $cookieData,
            new \DateTime('+1 year'), // expiration time, if applicable
            '/', // path, if applicable
            null, // domain
            false, // secure only
            true, // http only
            CookieInterface::SAMESITE_STRICT
        );
        $response = $this->getController()->getResponse()->withCookie($shopCookie);
        $this->getController()->setResponse($response);
    }

    /**
     * Reset session
     */
    public function resetSession()
    {
        $this->getController()->getRequest()->getSession()->delete('Shop.Cart');
        $this->getController()->getRequest()->getSession()->delete('Shop.Order');
    }

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
            throw new \Exception('Fatal error: Failed to create cart order');
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
        $cartId = $this->cartId;

        //debug("resuming order with cardid " . $this->cartId);
        if (!$this->order || $options['force']) {
            //@TODO check if cart is owned by customer
            $this->order = $this->ShopOrders->find('cart', [
                //'sessionid' => $this->sessionId,
                'cartid' => $cartId,
                //'shop_customer_id IS' => $this->Shop->getCustomerId(),
                'is_temporary' => true,
            ])->first();

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
