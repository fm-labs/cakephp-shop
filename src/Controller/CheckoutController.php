<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/17/15
 * Time: 1:04 AM
 */

namespace Shop\Controller;


use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Routing\Router;
use Shop\Lib\LibShopCart;
use Shop\Model\Table\ShopAddressesTable;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutController
 * @package Shop\Controller
 *
 * @property ShopOrdersTable $ShopOrders
 */
class CheckoutController extends AppController
{
    public $modelClass = "Shop.ShopOrders";

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        //$this->Auth->allow(['cart', 'customer','customerSignup', 'customerGuest', 'billing', 'shipping', 'payment', 'review', 'success']);
    }

    public function beforeRender(Event $event)
    {
    }

    public function index()
    {
        $this->Checkout->redirectNext();
    }

    public function next()
    {
        $this->Checkout->redirectNext();
    }

    public function step($step = null)
    {
        if (!$step) {
            throw new \InvalidArgumentException('Checkout step not defined');
        }

        if ($step === 'next') {
            return $this->Checkout->redirectNext();
        }

        $step = $this->Checkout->getStep($step);
        $step->execute($this);
    }

}