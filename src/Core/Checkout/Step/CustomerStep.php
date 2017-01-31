<?php
namespace Shop\Core\Checkout\Step;


use Cake\Controller\Controller;
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
            $this->_executeLogin($controller);
        }
        elseif ($controller->request->query('signup')) {
            $this->_executeSignup($controller);
        }
        elseif ($controller->request->query('guest')) {
            $this->_executeGuest($controller);
        } else {
            $controller->render('customer');
        }
    }

    protected function _executeLogin(Controller $controller)
    {
        if ($controller->request->is(['put', 'post'])) {
            $redirect = $controller->Auth->login();

            $controller->loadModel('Shop.ShopCustomers');
            if ($controller->Auth->user()) {
                $customer = $controller->ShopCustomers
                    ->find()
                    ->where([
                        'ShopCustomers.user_id' => $controller->Auth->user('id'),
                    ])
                    ->first();

                if ($customer) {
                    $controller->Shop->setCustomer($customer);
                }
                $controller->Flash->success(__d('shop','Logged in as {0}', $controller->Auth->user('username')));
                //if ($redirect) {
                //    $this->redirect($redirect);
                //}
            } else {
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

            $customer = $controller->ShopCustomers->add($controller->request->data);
            if ($customer && !$customer->errors()) {

                debug($customer->toArray());

                if ($customer->user_id) {

                    $controller->loadModel('User.Users');
                    $userQuery = $controller->Users->find()->where(['Users.id' => $customer->user_id]);
                    $user = $controller->Users->findAuthUser($userQuery, [])->first();
                    debug($user);
                    /*
                    $userQuery = $controller->Auth->userModel()->find()->where();
                    $user = $controller->userModel()->findAuthUser($userQuery)->first();
                    */
                    if ($user) {
                        $controller->Auth->setUser($user->toArray());
                        $controller->Flash->success(__d('shop','Customer signup successful'));
                        $this->Checkout->redirectNext();
                    } else {
                        $controller->Flash->error(__d('shop','Customer login after signup failed'));
                        Log::error('Customer login after signup failed for customerID ' . $customer->id);
                    }
                }

            } else {
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