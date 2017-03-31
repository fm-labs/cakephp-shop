<?php

namespace Shop\Controller;

use Cake\Event\Event;
use Content\Controller\ContentController;
use Content\Controller\Component\FrontendComponent;
use Cake\Controller\Component\AuthComponent;
use Cake\Utility\Text;
use Shop\Controller\Component\CartComponent;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property FrontendComponent $Frontend
 * @property AuthComponent $Auth
 * @property CartComponent $Cart
 */
class AppController extends ContentController
{

    public function initialize()
    {
        parent::initialize();

        $this->helpers['Paginator'] = [
            'templates' => 'Shop.paginator_templates' // @TODO copy paginator templates to app dir. DRY!?
        ];

        $this->loadComponent('Content.Locale');
        $this->loadComponent('Shop.Shop');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        if ($this->components()->has('Auth')) {
            $this->Auth->allow();
            $this->Auth->config('logoutRedirect', ['_name' => 'shop:index']);
        }
    }
}
