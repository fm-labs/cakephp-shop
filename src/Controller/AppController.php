<?php

namespace Shop\Controller;

use Cake\Event\Event;
use Content\Controller\ContentController;
use Content\Controller\Component\FrontendComponent;
use Cake\Controller\Component\AuthComponent;
use Cake\Utility\Text;
use Shop\Controller\Component\CartComponent;
use Shop\Controller\Component\ShopComponent;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property ShopComponent $Shop
 * @property AuthComponent $Auth
 * @property CartComponent $Cart
 */
class AppController extends ContentController
{

    public function initialize()
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Shop.Shop');
        $this->loadComponent('Shop.Shop');

        $this->Auth->setConfig('logoutRedirect', ['_name' => 'shop:index']);
    }
}
