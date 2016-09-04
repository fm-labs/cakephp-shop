<?php

namespace Shop\Controller;

use App\Controller\AppController as BaseAppController;
use Cake\Utility\Text;
use Shop\Lib\LibShopCart;
use Shop\Model\Table\ShopOrdersTable;

class AppController extends BaseAppController
{

    public function initialize()
    {

        $this->helpers['Paginator'] = [
            'templates' => 'Shop.paginator_templates' // @TODO copy paginator templates to app dir. DRY!?
        ];


        $this->loadComponent('Banana.Frontend');

        parent::initialize();

        if ($this->components()->has('Auth')) {
            $this->Auth->allow();
        }
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
