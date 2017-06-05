<?php

namespace Shop\Controller;


use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Shop\Controller\Component\CheckoutComponent;
use Shop\Model\Table\ShopOrdersTable;

/**
 * Class CheckoutController
 *
 * @package Shop\Controller
 *
 * @property ShopOrdersTable $ShopOrders
 * @property CheckoutComponent $Checkout
 */
class CheckoutController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopOrders";

    /**
     * Initialize
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Shop.Shop');
        $this->loadComponent('Shop.Cart');
        $this->loadComponent('Shop.Checkout');

        $this->Auth->allow(['index', 'next']);
    }

    /**
     * @param Event $event
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout((Configure::read('Shop.Checkout.layout')) ?: null); //@TODO Move layout handling to ShopComponent
    }

    /**
     * @param Event $event
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        $this->set('steps', $this->Checkout->describeSteps());
    }

    /**
     * Checkout index action
     * Load order from cartID and redirect to next checkout step
     *
     * @param null|string $cartId
     * @return \Cake\Network\Response|null
     */
    public function index($cartId = null)
    {
        if (!$cartId) {
            //@TODO Log bad request
            $this->Flash->error(__d('shop', 'Something went wrong. Please try again.'));
            return $this->redirect(['_name' => 'shop:cart']);
        }

        $this->Checkout->initFromCartId($cartId);
        return $this->Checkout->redirectNext();
    }

    /**
     * @param null|string $cartId
     * @deprecated Use index() instead
     */
    public function next($cartId = null)
    {
        $this->setAction('index', $cartId);
    }

    /**
     * Invokes controller actions or fallback to checkout step,
     * where the controller action is mapped to checkout stepID of same name
     *
     * @return \Cake\Network\Response|mixed|null
     */
    public function invokeAction()
    {
        try {
            return parent::invokeAction();

        } catch (MissingActionException $ex) {

            // read stepID from request
            $stepId = $this->request->params['action'];
            $stepId = Inflector::underscore($stepId);

            // read cartID from request
            $cartId = $this->request->param('cartid');
            if (!$cartId) {
                //@TODO Log bad request
                $this->Flash->error(__d('shop', 'Something went wrong. Please try again.'));
                return $this->redirect(['_name' => 'shop:cart']);
            }

            // load order for cartID
            $this->Checkout->initFromCartId($cartId);

            // execute checkout step
            return $this->Checkout->executeStep($stepId);
        }

        throw new MissingActionException([
            'controller' => $this->name . "Controller",
            'action' => $this->request->params['action'],
            'prefix' => isset($this->request->params['prefix']) ? $this->request->params['prefix'] : '',
            'plugin' => $this->request->params['plugin'],
        ]);
    }
}