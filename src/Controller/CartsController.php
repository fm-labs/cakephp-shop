<?php
declare(strict_types=1);

namespace Shop\Controller;

use Cake\ORM\Locator\TableLocator;

/**
 * Class CartsController
 *
 * @package Shop\Controller
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class CartsController extends AppController
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
    }

    /**
     * @return void
     */
    public function index()
    {
        // Find all stale carts from customer
        $carts = [];
        if ($this->Shop->getCustomerId()) {
            $carts = $this->ShopOrders->find()
                ->where([
                    'shop_customer_id' => $this->Shop->getCustomerId(),
                    'status' => 0
                ])
                ->contain(['ShopOrderItems'])
                ->orderDesc('id')
                ->all()
                ->toArray();
        }
        $this->set('carts', $carts);
    }

    public function restore(?string $cartId = null)
    {
        /** @var ?\Shop\Model\Entity\ShopOrder $cart */
        $cart = null;
        if ($this->Shop->getCustomerId()) {
            $cart = $this->ShopOrders->find()
                ->where([
                    'cartid' => $cartId,
                    'shop_customer_id' => $this->Shop->getCustomerId(),
                    'status' => 0
                ])
                ->orderDesc('id')
                ->first();
        }

        if ($cart) {
            $this->Cart->setOrder($cart, true);
            $this->Cart->updateSession();
            //$this->Cart->restoreFromId($cartId);
            $this->Flash->success(__d('shop', 'Cart restored'));
        } else {
            $this->Flash->error(__d('shop', 'Cart not found'));
        }

        $this->redirect(['controller' => 'Cart', 'action' => 'index']);
    }
}
