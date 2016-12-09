<?php

namespace Shop\Controller;

use Cake\Event\Event;
use Content\Controller\AppController as ContentAppController;
use Content\Controller\Component\FrontendComponent;
use Cake\Controller\Component\AuthComponent;
use Cake\Utility\Text;
use Shop\Lib\LibShopCart;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property FrontendComponent $Frontend
 * @property AuthComponent $Auth
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
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
        $this->Auth->config('logoutRedirect', '/');
    }

    /**
     * @return LibShopCart
     */
    protected function _getCart($cartid = null)
    {
        $sessionid = $this->request->session()->id();

        if ($cartid === null) {
            $cartid = $this->request->session()->read('Shop.Checkout.cartId');
        }

        return new LibShopCart($sessionid, $cartid);
    }

    /**
     *
     */
    protected function _writeCartToSession()
    {
        $this->request->session()->write('Shop.Checkout', $this->cart->toArray());
    }

    protected function _resetCartSession()
    {
        $this->request->session()->delete('Shop.Checkout');
    }
}
