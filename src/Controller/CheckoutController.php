<?php

namespace Shop\Controller;


use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Inflector;
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

        $this->loadComponent('Shop.Shop');
        $this->loadComponent('Shop.Cart');
        $this->loadComponent('Shop.Checkout');

        $this->Auth->allow();
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $layout = (Configure::read('Shop.Checkout.layout')) ?: null;
        $this->viewBuilder()->layout($layout);
    }

    public function beforeRender(Event $event)
    {
        $this->set('steps', $this->Checkout->describeSteps());
    }

    protected function _checkOrder()
    {
        if (!$this->Checkout->getOrder() || $this->Cart->getItemsCount() < 1) {
            $this->Flash->error(__d('shop', 'Checkout aborted: Your cart is empty'));
            $this->redirect(['_name' => 'shop:cart']);
            return;
        }
    }

    protected function _checkCart($cartid)
    {

        if ($this->Checkout->getOrder() && $this->Checkout->getOrder()->cartid != $cartid) {
            $this->Flash->error(__d('shop', 'Checkout aborted: Bad request'));
            //$this->redirect(['_name' => 'shop:cart']);
            return;
        }
    }

    public function debug()
    {
        if (!Configure::read('debug')) {
            throw new NotFoundException();
        }
    }

    public function invokeAction()
    {
        try {
            return parent::invokeAction();

        } catch (MissingActionException $ex) {
            $stepId = $this->request->params['action'];
            $stepId = Inflector::underscore($stepId);

            $cartId = $this->request->param('cartid');
            if (!$cartId) {
                // If no cartId given, use the one from the cart component
                $cartId = $this->Checkout->Cart->getCartId();
                if (!$cartId) {
                    throw new BadRequestException("Unknown cartid for step " . $stepId);
                }
            }

            // check if cart order exists
            $this->_checkOrder();
            $this->_checkCart($cartId);

            // check step
            if ($stepId === 'next' || $stepId === 'index') {
                return $this->Checkout->redirectNext();
            }

            if (!$this->Checkout->getStep($stepId)) {
                throw new BadRequestException("Unknown checkout step " . $stepId);
            }

            // check if all previous steps are completed
            if (!$this->Checkout->checkStep($stepId)) {
                return $this->Checkout->redirectNext();
            }

            // execute step
            $step = $this->Checkout->getStep($stepId);

            $event = $this->eventManager()->dispatch(new Event('Shop.Checkout.beforeStep', $this, compact('step')));

            $response = $this->Checkout->executeStep($step);

            $event = $this->eventManager()->dispatch(new Event('Shop.Checkout.afterStep', $this, compact('step', 'response')));

            return $response;
        }

        throw new MissingActionException([
            'controller' => $this->name . "Controller",
            'action' => $this->request->params['action'],
            'prefix' => isset($this->request->params['prefix']) ? $this->request->params['prefix'] : '',
            'plugin' => $this->request->params['plugin'],
        ]);
    }
}