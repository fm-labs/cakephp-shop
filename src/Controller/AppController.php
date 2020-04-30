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
 * @property \Shop\Controller\Component\ShopComponent $Shop
 * @property \Shop\Controller\Component\CartComponent $Cart
 */
class AppController extends BaseAppController
{
    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()->setClassName('Shop.Shop');
        $this->loadComponent('Content.Frontend');
        $this->loadComponent('Shop.Shop');

        if (!$this->components()->has('Authentication')) {
            throw new Exception('Shop requires an authentication component to be loaded');
        }
    }
}
