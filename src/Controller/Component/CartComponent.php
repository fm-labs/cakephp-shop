<?php

namespace Shop\Controller\Component;


use Cake\Controller\Component;
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
 */
class CartComponent extends Component
{

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

        $this->_init();
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

    public function addItem($refid = null, $amount = 1)
    {
        $this->_resumeOrder(['create' => true]);

        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.Item.beforeAdd'));

        $item = null;
        foreach ($this->order->shop_order_items as $_item) {
            if ($_item->refid == $refid) {
                $item = $_item;
                break;
            }
        }

        if ($item && $amount == 0) {
            //return $this->removeItem($item->shop_order_id, $item->id);
        } elseif ($amount < 0) {
            $amount = abs($amount);
        }

        if (!$item) {

            $product = $this->ShopProducts->get($refid);


            $item = $this->ShopOrders->ShopOrderItems->newEntity([
                'refscope' => 'Shop.ShopProducts',
                'refid' => $refid,
                'shop_order_id' => $this->order->id,
                'amount' => $amount,
                'title' => $product->title,
                'unit' => ($product->unit) ?: 'x',
                'item_value_net' => $product->price_net,
                'tax_rate' => $product->tax_rate
            ]);
        } else {
            $item->amount += $amount;
        }

        $item->calculate();

        if (!$this->ShopOrders->ShopOrderItems->save($item)) {
            debug($item->errors());
            Log::debug('Failed to add order item to order with ID ' . $this->order->id);
            return false;
        }

        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.Item.afterAdd'));
        Log::debug('Added order item to order with ID ' . $this->order->id);
        $this->_resumeOrder(['force' => true]);
        return true;
    }

    public function updateItem($orderItemId, $data = [])
    {
        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.Item.beforeUpdate'));

        $orderItem = $this->ShopOrders->ShopOrderItems->get($orderItemId, ['contain' => []]);
        $orderItem->accessible('*', false);
        $orderItem->accessible('amount', true);
        $this->ShopOrders->ShopOrderItems->patchEntity($orderItem, $data);
        $success = $this->ShopOrders->ShopOrderItems->save($orderItem);

        $this->_registry->getController()->eventManager()->dispatch(new Event('Shop.Cart.Item.afterUpdate'));

        return $success;
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

            $this->order = $this->ShopOrders
                ->find()
                ->where([
                    //'sessionid' => $this->sessionId,
                    'cartid' => $this->cartId,
                    'is_temporary' => true,
                ])
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