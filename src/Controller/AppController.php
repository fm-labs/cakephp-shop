<?php
declare(strict_types=1);

namespace Shop\Controller;

use App\Controller\AppController as BaseAppController;
use Cake\Core\Exception\Exception;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property \Shop\Controller\Component\ShopComponent $Shop
 * @property \Cake\Controller\Component\AuthComponent $Auth
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class AppController extends BaseAppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Shop.Shop');
        $this->loadComponent('Shop.Shop');

        if (!$this->components()->has('Auth')) {
            throw new Exception('Shop requires an authentication component to be loaded');
        }

        $this->components()->get('Auth')->setConfig('logoutRedirect', ['_name' => 'shop:index']);
    }
}
