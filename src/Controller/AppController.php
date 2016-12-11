<?php

namespace Shop\Controller;

use Cake\Event\Event;
use Content\Controller\AppController as ContentAppController;
use Content\Controller\Component\FrontendComponent;
use Cake\Controller\Component\AuthComponent;
use Cake\Utility\Text;
use Shop\Controller\Component\CartComponent;
use Shop\Lib\LibShopCart;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property FrontendComponent $Frontend
 * @property AuthComponent $Auth
 * @property CartComponent $Cart
 */
class AppController extends ContentAppController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers['Paginator'] = [
            'templates' => 'Shop.paginator_templates' // @TODO copy paginator templates to app dir. DRY!?
        ];

        $this->loadComponent('Shop.Shop');
        $this->loadComponent('Shop.Cart');
        $this->loadComponent('Shop.Checkout');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
        $this->Auth->config('logoutRedirect', '/');
    }

    /**
     * @return CartComponent
     * @deprecated Use CartComponent directly instead
     */
    protected function _getCart($cartid = null)
    {
        return $this->Cart;
    }

    /**
     * @deprecated Use CartComponent directly instead
     */
    protected function _writeCartToSession()
    {
        $this->Cart->updateSession();
    }

    /**
     * @deprecated Use CartComponent directly instead
     */
    protected function _resetCartSession()
    {
        $this->Cart->resetSession();
    }
}
