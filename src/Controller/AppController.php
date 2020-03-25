<?php
declare(strict_types=1);

namespace Shop\Controller;

use Cake\Controller\Controller;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property \Shop\Controller\Component\ShopComponent $Shop
 * @property \Cake\Controller\Component\AuthComponent $Auth
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Shop.Shop');
        $this->loadComponent('Shop.Shop');

        $this->Auth->setConfig('logoutRedirect', ['_name' => 'shop:index']);
    }
}
