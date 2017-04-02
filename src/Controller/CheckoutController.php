<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/17/15
 * Time: 1:04 AM
 */

namespace Shop\Controller;


use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use LogicException;
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

        $this->loadComponent('Shop.Checkout');
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

        //debug($this->Checkout->describeSteps());
        $this->Checkout->redirectNext();
    }

    public function invokeAction()
    {
        try {
            return parent::invokeAction();
        } catch (MissingActionException $ex) {
            $stepId = $this->request->params['action'];

            if ($stepId === 'next') {
                return $this->Checkout->redirectNext();
            }

            //@TODO throw MissingOrderException instead, or silently create order
            if (!$this->Checkout->getOrder()) {
                return $this->redirect(['_name' => 'shop:cart']);
            }

            if (!$this->Checkout->checkStep($stepId)) {
                return $this->Checkout->redirectNext();
            }

            $step = $this->Checkout->getStep($stepId);
            return $step->execute($this);
        }

        throw new MissingActionException([
            'controller' => $this->name . "Controller",
            'action' => $request->params['action'],
            'prefix' => isset($request->params['prefix']) ? $request->params['prefix'] : '',
            'plugin' => $request->params['plugin'],
        ]);
    }

    public function complete()
    {
        $uuid = $this->request->query('uuid');
        if (!$uuid) {
            throw new BadRequestException();
        }

        debug($uuid);

        $order = $this->ShopOrders->find()
            ->where(['ShopOrders.uuid' => $uuid])
            ->first();

        $this->set('shopOrder', $order);
    }

}