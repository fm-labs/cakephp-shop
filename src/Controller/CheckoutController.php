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
use Cake\Network\Exception\NotFoundException;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutController
 * @package Shop\Controller
 *
 * @property ShopOrdersTable $ShopOrders
 * @property CheckoutComponent $Checkout
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
        $this->viewBuilder()->layout(Configure::read('Shop.Checkout.layout'));
    }

    public function beforeRender(Event $event)
    {
        $this->set('steps', $this->Checkout->describeSteps());
    }

    public function debug()
    {
        if (!Configure::read('debug')) {
            throw new NotFoundException();
        }

    }

    public function index()
    {
        $op = $this->request->query('op');

        if ($op == 'cancel') {
            $this->Checkout->reset();
            $this->Flash->success(__d('shop', 'The order has been aborted'));
            $this->redirect(['_name' => 'shop:cart']);
        }

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

        if (!$this->Checkout->getOrder()) {
            return $this->redirect(['_name' => 'shop:cart']);
        }

        $step = $this->Checkout->getStep($step);
        $this->render('index');
        $step->execute($this);
    }

}