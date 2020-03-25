<?php
declare(strict_types=1);

namespace Shop\Core\Checkout\Step;

use Cake\Controller\Controller;
use Shop\Core\Checkout\CheckoutStepInterface;

/**
 * Class CustomerStep
 *
 * @package Shop\Checkout
 * @property \Shop\Model\Table\ShopCustomersTable $ShopCustomers
 */
class CustomerStep extends BaseStep implements CheckoutStepInterface
{
    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->Checkout->Shop->getCustomer() ? true : false;
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return bool|\Cake\Http\Response
     * @throws \Exception
     */
    public function execute(Controller $controller)
    {
        if ($controller->getRequest()->getData('op') == 'login') {
            return $this->_executeLogin($controller);
        } elseif (
            $controller->getRequest()->getData('op') == 'signup'
            || $controller->getRequest()->getQuery('op') == 'signup'
        ) {
            return $this->_executeSignup($controller);
        //} elseif ($controller->getRequest()->getQuery('guest')) {
        //    $controller->getRequest()->data['nologin'] = true;
        //    return $this->_executeSignup($controller);
        }

        return $controller->render('customer');
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return bool|\Cake\Http\Response
     * @throws \Exception
     */
    protected function _executeLogin(Controller $controller)
    {
        // check if already authenticated
        if ($controller->Auth->user('id')) {
            return true;
        }

        //  POST request
        if ($controller->getRequest()->is(['put', 'post'])) {
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

            /*
                // force creation of customer for user
                if (!$customer) {
                    $this->log('Create customer for user with id ' . $user->id);
                    $controller->loadModel('Shop.ShopCustomers');
                    $customer = $controller->ShopCustomers->createFromUserId($user->id);
                }
            */

                if (!$customer) {
                    $this->log('Create customer for user with id ' . $user->id, LOG_ERR);
                    throw new \Exception('CustomerStep: Failed to create customer');
                }

                // set customer in shop scope
                $this->Checkout->Shop->setCustomer($customer);

                // link customer to order (persistent)
                $this->Checkout->getOrder()->shop_customer_id = $customer->id;
                $this->Checkout->saveOrder();

                // redirect to next step
                $controller->Flash->success(__d('shop', 'Logged in as {0}', $controller->Auth->user('username')));

                return true;
            } else {
                debug("login failed");
                $controller->Flash->error(__d('shop', 'Login failed :('));
            }
        }

        return $controller->render('customer');
    }

    /**
     * @param \Cake\Controller\Controller $controller
     * @return bool|\Cake\Http\Response
     */
    protected function _executeSignup(Controller $controller)
    {
        $controller->loadModel('Shop.ShopCustomers');
        $customer = $controller->ShopCustomers->newEntity();
        $user = $controller->ShopCustomers->Users->newEntity(null, ['validate' => 'register']);
        if ($controller->getRequest()->is(['put', 'post'])) {
            //debug($controller->getRequest()->data);
            //$customer = $controller->ShopCustomers->add($customer, $controller->getRequest()->data);
            $user = $controller->ShopCustomers->Users->register($controller->getRequest()->getData());
            if ($user && $user->id) {
                // authenticate user
                // @TODO Make 'automatic user login after signup' configurable
                //$userQuery = $controller->ShopCustomers->Users->find()->where(['Users.id' => $customer->user_id]);
                //$user = $controller->ShopCustomers->Users->findAuthUser($userQuery, [])->first();
                $controller->Auth->setUser($user->toArray());
                //$controller->getEventManager()->dispatch(new Event('User.Auth.login', $controller, compact('user')));

                // create a shop customer profile for user
                $customer = $controller->ShopCustomers->createFromUser($user, $controller->getRequest()->getData());
                // set customer in shop scope
                $this->Checkout->Shop->setCustomer($customer);

                // link customer to order (persistent)
                $this->Checkout->getOrder()->shop_customer_id = $customer->id;
                $this->Checkout->saveOrder();

                // continue to next step
                $controller->Flash->success(__d('shop', 'Signup was successful'));

                return true;
            } else {
                $controller->Flash->error(__d('shop', 'Please fill all required fields'));
            }
        }
        $controller->set('user', $user);

        return $controller->render('customer_signup');
    }
}
