<?php
declare(strict_types=1);

namespace Shop\Controller;

use App\Controller\AppController as BaseAppController;
use Cake\Core\Exception\Exception;

/**
 * Class AppController
 *
 * @package Shop\Controller
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Cupcake\Controller\Component\ThemeComponent $Theme
 * @property \User\Controller\Component\AuthComponent $Auth
 * @property \Content\Controller\Component\FrontendComponent $Frontend
 * @property \Shop\Controller\Component\ShopComponent $Shop
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class AppController extends BaseAppController
{
    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Flash = $this->loadComponent('Flash');
        $this->Auth = $this->loadComponent('User.Auth');
        $this->Authentication = $this->Auth->Authentication;
        //$this->loadComponent('Authentication.Authentication'); // loaded by User.Auth component

        $this->Theme = $this->loadComponent('Cupcake.Theme');
        $this->Frontend = $this->loadComponent('Content.Frontend');
        $this->Shop = $this->loadComponent('Shop.Shop');

        if (!$this->components()->has('Authentication')) {
            throw new Exception('Shop requires an authentication component to be loaded');
        }

        $this->viewBuilder()->setClassName('Shop.Shop');
    }
}
