<?php
namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Log\Log;
use Shop\Core\Checkout\CheckoutStepInterface;
use Shop\Model\Table\ShopCustomersTable;

/**
 * Class CustomerStep
 * @package Shop\Checkout
 * @property ShopCustomersTable $ShopCustomers
 */
class CustomerStep extends BaseStep implements CheckoutStepInterface
{

    public function isComplete()
    {
        return ($this->Checkout->Shop->getCustomer()) ? true : false;
    }

    public function execute(Controller $controller)
    {
        if ($controller->request->query('login')) {
            return $this->_executeLogin($controller);
        }
        elseif ($controller->request->query('signup')) {
            return $this->_executeSignup($controller);
        }
        elseif ($controller->request->query('guest')) {
            $this->_executeGuest($controller);
        } else {
            $controller->render('customer');
        }
    }

    protected function _executeLogin(Controller $controller)
    {
        // check if already authenticated
        if ($controller->Auth->user('id')) {
            return $this->Checkout->redirectNext();
        }

        //  POST request
        if ($controller->request->is(['put', 'post'])) {

            // try to authenticate user
            $controller->Auth->login();

            // find customer for authenticated user
            $user = $controller->Auth->user();
            if ($user) {
                $controller->loadModel('Shop.ShopCustomers');
                $customer = $controller->ShopCustomers
                    ->find()
                    ->where([
                        'ShopCustomers.user_id' => $controller->Auth->user('id'),
                    ])
                    ->first();

                // force creation of customer for user
                if (!$customer) {
                    $this->log('Create customer for user with id ' . $user->id);
                    $controller->loadModel('Shop.ShopCustomers');
                    $customer = $controller->ShopCustomers->createFromUserId($user->id);
                }

                if (!$customer) {
                    $this->log('Create customer for user with id ' . $user->id, LOG_ERR);
                    throw new \Exception('CustomerStep: Failed to create customer');
                }

                // set customer in shop scope
                $this->Checkout->Shop->setCustomer($customer);

                // link customer to order (persistent)
                $this->Checkout->Cart->getOrder()->shop_customer_id = $customer->id;
                $this->Checkout->Cart->saveOrder();

                // update the order in session
                $this->Checkout->Cart->updateSession();

                $controller->Flash->success(__d('shop','Logged in as {0}', $controller->Auth->user('username')));

                // redirect to next step
                $this->Checkout->redirectNext();
            } else {
                debug("login failed");
                $controller->Flash->error(__d('shop','Login failed :('));
            }
        }
        $controller->render('customer');
    }

    protected function _executeSignup(Controller $controller)
    {
        $controller->loadModel('Shop.ShopCustomers');
        $customer = $controller->ShopCustomers->newEntity();
        if ($controller->request->is(['put', 'post'])) {

            //debug($controller->request->data);
            $customer = $controller->ShopCustomers->add($controller->request->data);
            if ($customer && !$customer->errors()) {

                if ($customer->user_id) {

                    $controller->loadModel('User.Users');
                    $userQuery = $controller->Users->find()->where(['Users.id' => $customer->user_id]);
                    $user = $controller->Users->findAuthUser($userQuery, [])->first();
                    //debug($user);
                    /*
                    $userQuery = $controller->Auth->userModel()->find()->where();
                    $user = $controller->userModel()->findAuthUser($userQuery)->first();
                    */
                    if ($user) {
                        $controller->Auth->setUser($user->toArray());
                        $controller->eventManager()->dispatch(new Event('User.login', $controller, compact('user')));

                        $this->Checkout->Shop->setCustomer($customer);

                        $this->Checkout->Cart->getOrder()->shop_customer_id = $customer->id;
                        $this->Checkout->Cart->saveOrder();
                        $this->Checkout->Cart->updateSession();

                        $controller->Flash->success(__d('shop','Customer signup successful'));
                        return $this->Checkout->redirectNext();
                    } else {
                        $controller->Flash->error(__d('shop','Customer login after signup failed'));
                        Log::error('Customer login after signup failed for customerID ' . $customer->id);
                    }
                }

            } else {
                debug($customer->errors());
                $controller->Flash->error(__d('shop','Customer signup failed'));
            }
        }
        $controller->set('newCustomer', $customer);
        $controller->render('customer_signup');
    }

    protected function _executeGuest(Controller $controller)
    {
        if ($controller->request->is(['post', 'put'])) {

        }
        $controller->render('customer_guest');
    }
}