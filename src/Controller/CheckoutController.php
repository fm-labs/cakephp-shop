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

        /*
        if (!$this->Checkout->isReady()) {
            $this->Flash->error(__d('shop', 'Checkout aborted: Invalid request'));
            $this->redirect(['_name' => 'shop:cart']);
            return;
        }
        */

        $layout = (Configure::read('Shop.Checkout.layout')) ?: null;

        //$this->Auth->allow(['cart', 'customer','customerSignup', 'customerGuest', 'billing', 'shipping', 'payment', 'review', 'success']);
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

    public function debug()
    {
        if (!Configure::read('debug')) {
            throw new NotFoundException();
        }
    }

    public function index()
    {
        $this->_checkOrder();

        // redirect to next step
        $redirect = $this->Checkout->redirectUrl();
        if ($redirect) {
            return $this->redirect($redirect);
        }

        Log::alert('Checkout Trap for order ', $this->Checkout->getOrder()->id);
        $this->Flash->error(__d('shop', 'Sorry, but we are unable to process your order at the moment. Please try again later.'));
        $this->redirect(['_name' => 'shop:cart']);
    }

    public function invokeAction()
    {
        try {
            return parent::invokeAction();

        } catch (MissingActionException $ex) {
            $stepId = $this->request->params['action'];
            $stepId = Inflector::underscore($stepId);

            // check if cart order exists
            $this->_checkOrder();

            // check step
            if ($stepId === 'next') {
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