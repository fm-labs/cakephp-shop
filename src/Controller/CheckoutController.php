<?php
declare(strict_types=1);

namespace Shop\Controller;

use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Closure;

/**
 * Class CheckoutController
 *
 * @package Shop\Controller
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 * @property \Shop\Controller\Component\CheckoutComponent $Checkout
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
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Shop.Shop');
        $this->loadComponent('Shop.Cart');
        $this->loadComponent('Shop.Checkout');

        $this->Authentication->allowUnauthenticated(['index', 'next']);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        //$this->viewBuilder()->setLayout(Configure::read('Shop.Checkout.layout') ?: null); //@TODO Move layout handling to ShopComponent
    }

    /**
     * {@inheritDoc}
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        $this->set('steps', $this->Checkout->describeSteps());
    }

    /**
     * Checkout index action
     * Load order from cartID and redirect to next checkout step
     *
     * @param null|string $cartId
     * @return \Cake\Http\Response|null
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
        $this->Checkout->cleanup();
        $this->redirect(['action' => 'index', $cartId]);
    }

//    public function isAction(string $action): bool
//    {
//        try {
//            return parent::isAction($action);
//        } catch (MissingActionException $ex) {
//            if ($this->Checkout->hasStep($action)) {
//                return true;
//            }
//            throw $ex;
//        }
//    }

    public function getAction(): Closure
    {
        try {
            return parent::getAction();
        } catch (MissingActionException $ex) {
            $request = $this->request;
            $action = $request->getParam('action');
            $action = Inflector::underscore($action);
            if (!$this->Checkout->hasStep($action)) {
                throw $ex;
            }

            return function() {
                // read stepID from request
                $stepId = $this->request->getParam('action');
                $stepId = Inflector::underscore($stepId);

                // read cartID from request
                $cartId = $this->request->getParam('cartid');
                if (!$cartId) {
                    //@TODO Log bad request
                    $this->Flash->error(__d('shop', 'Something went wrong. Please try again.'));

                    $this->redirect(['_name' => 'shop:cart']);
                    return;
                }

                // load order for cartID
                $this->Checkout->initFromCartId($cartId);

                // execute checkout step
                $this->Checkout->executeStep($stepId);
            };
        }
    }

//    /**
//     * Invokes controller actions or fallback to checkout step,
//     * where the controller action is mapped to checkout stepID of same name
//     *
//     * @return \Cake\Http\Response|mixed|null
//     */
//    public function invokeAction(Closure $action, array $args): void
//    {
//        try {
//            parent::invokeAction($action, $args);
//            return;
//        } catch (MissingActionException $ex) {
//            debug($ex->getMessage());
//            die($ex->getMessage());
//            // read stepID from request
//            $stepId = $this->request->getParam('action');
//            $stepId = Inflector::underscore($stepId);
//
//            // read cartID from request
//            $cartId = $this->request->getParam('cartid');
//            if (!$cartId) {
//                //@TODO Log bad request
//                $this->Flash->error(__d('shop', 'Something went wrong. Please try again.'));
//
//                $this->redirect(['_name' => 'shop:cart']);
//                return;
//            }
//
//            // load order for cartID
//            $this->Checkout->initFromCartId($cartId);
//
//            // execute checkout step
//            $this->Checkout->executeStep($stepId);
//            return;
//        }
//
//        throw new MissingActionException([
//            'controller' => $this->name . "Controller",
//            'action' => $this->request->getParam('action'),
//            'prefix' => $this->request->getParam('prefix'),
//            'plugin' => $this->request->getParam('plugin'),
//        ]);
//    }
}
