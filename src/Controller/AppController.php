<?php

namespace Shop\Controller;

use Cake\Controller\Controller;
use Cake\Controller\Component\AuthComponent;
use Shop\Controller\Component\CartComponent;
use Shop\Controller\Component\ShopComponent;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property ShopComponent $Shop
 * @property AuthComponent $Auth
 * @property CartComponent $Cart
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
