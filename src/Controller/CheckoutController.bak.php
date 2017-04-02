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
    /**
     * @var LibShopCart
     */
    public $cart;

    public $steps;

    public $modelClass = "Shop.ShopOrders";

    public function initialize()
    {
        parent::initialize();

        $this->cart = $this->_getCart();
        $this->steps = [
            'cart' => [
                'title' => 'Warenkorb',
                'desc' => '',
                'icon' => 'shopping-basket',
                'class' => '',
                'complete' => null,
                '_complete' => function (LibShopCart $cart) {
                    return ($cart->getOrderItemsCount() > 0) ? true : false;
                }
            ],
            'customer' => [
                'title' => 'Kundendaten',
                'desc' => '',
                'icon' => 'user',
                'class' => '',
                'complete' => null,
                '_complete' => function (LibShopCart $cart) {
                    return ($cart->customer) ? true : false;
                }
            ],
            'billing' => [
                'title' => 'Rechnungsinfo eingeben',
                'desc' => '',
                'icon' => 'paper-plane',
                'class' => '',
                'complete' => null
            ],
            'shipping' => [
                'title' => 'Versand wählen',
                'desc' => '',
                'icon' => 'truck',
                'class' => '',
                'complete' => null
            ],
            'payment' => [
                'title' => 'Zahlungsart wählen',
                'desc' => '',
                'icon' => 'credit-card-alt',
                'class' => '',
                'complete' => null
            ],
            'review' => [
                'title' => 'Bestellung bestätigen',
                'desc' => 'Überprüfen Sie Ihre Bestellung',
                'icon' => 'thumbs-up',
                'class' => '',
                'complete' => null
            ]
        ];
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['cart', 'customer', 'billing', 'shipping', 'payment', 'review']);

        if ($this->request->param('action') !== 'success') {

            $this->_checkSteps();

            if (!$this->cart || !$this->cart->order) {
                $this->Flash->set(__d('shop','Order process canceled. No order found.'));
                $this->redirect(['controller' => 'Shop', 'action' => 'index']);
            }
        }

        // check customer
        if (!$this->cart->customer && $this->request->session()->check('Auth.User.id')) {
            // user login detected
            $userId = $this->request->session()->read('Auth.User.id');
            debug("detected user");
            $customer = $this->ShopOrders->ShopCustomers->find()
                ->where(['ShopCustomers.user_id' => $userId])
                ->first();

            if (!$customer) {
                $customer = $this->ShopOrders->ShopCustomers->newEntity();
                $customer->user_id = $userId;
                $customer->email = $this->Auth->user('email');
            }
            $this->cart->setCustomer($customer);
        }
        elseif ($this->cart->customer && $this->cart->customer->user_id && !$this->request->session()->check('Auth.User.id')) {
            // user logout detected
            $this->cart->resetCustomer();
            $this->cart->resetPayment();
            $this->cart->resetShipping();
        }
    }

    public function beforeRender(Event $event)
    {
        $this->_checkSteps();

        $this->set('cartId', $this->cart->cartId);
        $this->set('sessionId', $this->cart->sessionId);
        $this->set('order', $this->cart->order);
        $this->set('customer', $this->cart->customer);
        $this->set('steps', $this->steps);

        $this->_writeCartToSession();
    }

    protected function _checkSteps()
    {
        foreach ($this->steps as $method => &$step) {
            if (isset($step['_complete']) && is_callable($step['_complete'])) {
                $step['complete'] = call_user_func_array($step['_complete'], [$this->cart]);
            } else {
                $completeField = 'is_' . $method . '_selected';
                $step['complete'] = ($this->cart->order) ? $this->cart->order->get($completeField) : false;
            }

        }
    }

    protected function _getNextStep()
    {
        foreach ($this->steps as $method => $step) {
            if (!$step['complete']) {
                return $method;
            }
        }

        return 'review';
    }

    protected function _redirectNext($checkSteps = true, $checkRef = true)
    {
        if ($checkSteps) {
            $this->_checkSteps();
        }

        if ($checkRef) {
            if ($this->request->query('ref') == 'step' ||  $this->request->query('ref') == 'breadcrumb') {
                return;
            }
        }

        $next = $this->_getNextStep();
        $this->redirect(['action' => $next]);
    }


    public function index()
    {
        $this->_redirectNext(true, false);
    }

    public function next()
    {
        $this->_redirectNext();
    }


    public function customer()
    {
        $this->loadModel('Shop.ShopCustomers');
        $this->loadModel('User.Users');

        //$this->Auth->config('loginRedirect', ['action' => 'customer', 'login' => true]);
        $this->request->session()->write('Auth.redirect', Router::url(['action' => 'customer', 'login' => true]));

        if ($this->request->is(['put', 'post'])) {
            $redirect = $this->Auth->login();

            if ($this->Auth->user()) {
                $customer = $this->ShopCustomers
                    ->find()
                    ->where([
                        'ShopCustomers.user_id' => $this->Auth->user('id'),
                    ])
                    ->first();

                if ($customer) {
                    $this->cart->setCustomer($customer);
                    $this->_writeCartToSession();
                    $this->_redirectNext();
                    //$this->redirect(['action' => 'billing']);
                }
                $this->Flash->success(__d('shop','Logged in as {0}', $this->Auth->user('username')));
                //if ($redirect) {
                //    $this->redirect($redirect);
                //}
            } else {
                $this->Flash->error(__d('shop','Login failed :('));
            }
        }

        if ($this->Auth->user()) {
            $this->_redirectNext();
        }
    }


    public function customer_Bak()
    {
        $this->loadModel('Shop.ShopCustomers');

        if ($this->request->is(['put', 'post'])) {
            $email = trim($this->request->data('email'));
            $pw = trim($this->request->data('password'));

            //debug($email);
            //debug($pw);

            $pwHasher = $this->ShopCustomers->newEntity()->getPasswordHasher();

            $customer = $this->ShopCustomers
                ->find()
                ->where([
                    'ShopCustomers.email' => $email,
                    //'ShopCustomers.password' => $hashedPassword,
                    //'ShopCustomers.is_guest' => false,
                    //'ShopCustomer.is_blocked' => false,
                ])
                ->first();

            //debug($customer);

            if ($customer && $pwHasher->check($pw, $customer->password)) {
                $this->Flash->success(__d('shop','Login successful'));
                $this->cart->setCustomer($customer);
                $this->_writeCartToSession();
                $this->_redirectNext();
                //$this->redirect(['action' => 'billing']);

            } else {
                $this->Flash->error(__d('shop','Login failed'));
            }
        }
    }

    public function customerSignup()
    {
        //if ($this->request->session()->check('Shop.Checkout.Customer')) {
        //    $this->Flash->set('Customer already set in session');
        //}

        $customer = ($this->cart->customer) ?: $this->ShopOrders->ShopCustomers->newEntity();
        if ($this->request->is(['put', 'post'])) {

            $customer = $this->ShopOrders->ShopCustomers->patchEntity($customer, $this->request->data, ['validate' => 'add']);
            if ($this->ShopOrders->ShopCustomers->save($customer)) {

                $this->loadModel('User.Users');
                $user = $this->Users->newEntity();
                $user->name = $customer->email;
                $user->username = $customer->email;
                $user->email = $customer->email;
                $user->login_enabled = true;
                $user->password = $customer->password1;
                if ($this->Users->save($user)) {
                    $customer->user_id = $user->id;

                    Log::info('New shop customer ID ' . $customer->id . ' - New user ' . $user->id);
                    if (!$this->cart->setCustomer($customer)) {
                        $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
                    }
                    $this->_writeCartToSession();
                    $this->Auth->setUser($user);
                    $this->_redirectNext();
                } else {
                    debug($user->errors());
                    $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again'));
                }

            } else {
                debug($customer->errors());
            }
        }
        $this->set('newCustomer', $customer);
    }

    public function customerGuest()
    {
        //if ($this->request->session()->check('Shop.Checkout.Customer')) {
        //    $this->Flash->set('Customer already set in session');
        //}

        $customer = ($this->cart->customer) ?: $this->ShopOrders->ShopCustomers->newEntity();
        if ($this->request->is(['put', 'post'])) {

            $customer = $this->ShopOrders->ShopCustomers->patchEntity($customer, $this->request->data, ['validate' => 'addGuest']);
            if ($this->ShopOrders->ShopCustomers->save($customer)) {

                Log::info('Customer added with ID ' . $customer->id);
                $this->cart->setCustomer($customer);
                $this->_writeCartToSession();
                $this->_redirectNext();
            }
            debug($customer->errors());
        }
        $this->set('newCustomer', $customer);
    }

    public function resetCustomer()
    {
        $this->cart->resetCustomer();
        $this->_writeCartToSession();

        $this->redirect(['action' => 'customer']);
    }

    public function cart()
    {
        if (!$this->cart->order) {
            $this->redirect(['controller' => 'Shop', 'action' => 'index']);
        }
    }

    public function billing()
    {
        $billingAddresses = [];
        $billingAddress = $this->cart->getBillingAddress();

        if ($this->request->is(['put', 'post'])) {
            $billingAddress->accessible(['type', 'shop_customer_id'], false);
            $billingAddress = $this->ShopOrders->BillingAddresses->patchEntity($billingAddress, $this->request->data);
            if (!$billingAddress->errors()) {
                $this->cart->setBillingAddress($billingAddress)->saveOrder();
            }
            $this->_redirectNext();

            /*
            if ($this->ShopOrders->BillingAddresses->save($billingAddress) && $this->cart->setBillingAddressById($billingAddress->id)) {
                $this->Flash->success(__d('shop','Billing information has been updated'));

                //$this->redirect(['action' => 'billing']);
                $this->_redirectNext();
            } else {
                $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            }
            */
        }

        if ($this->cart->customer->id) {
            $billingAddresses = $this->ShopOrders->BillingAddresses
                ->find()
                ->where(['shop_customer_id' => $this->cart->customer->id])
                ->all()
                ->toArray();
        }

        $this->set('billingAddress', $billingAddress);
        $this->set('billingAddresses', $billingAddresses);

    }

    public function billingSelect($addressId = null)
    {

        if ($this->cart->setBillingAddressById($addressId)) {
            $this->Flash->success(__d('shop','Billing information has been updated'));
            $this->_redirectNext();
        } else {
            $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            $this->setAction('billing');
        }
    }


    public function shippingSelect($addressId)
    {
        if ($this->cart->setShippingAddressById($addressId)) {
            $this->Flash->success(__d('shop','Shipping information has been updated'));
            $this->_redirectNext();
        } else {
            $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            $this->redirect(['action' => 'shipping']);
        }
    }

    public function shippingType()
    {
        if ($this->request->is(['put', 'post'])) {
            //$this->cart->order = $this->ShopOrders->patchEntity($this->cart->order, $this->request->data);
            $this->cart->patchOrderShipping($this->request->data);
            if ($this->cart->saveOrder()) {
                $this->Flash->success(__d('shop','Shipping information has been updated'));
                $this->_redirectNext();
            } else {
                $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            }
        }
        $this->redirect(['action' => 'shipping']);
    }

    public function shipping()
    {
        $shippingAddresses = [];
        $shippingAddress = $this->cart->getShippingAddress();

        if ($this->request->is(['put', 'post'])) {
            $shippingAddress->accessible(['type', 'shop_customer_id'], false);
            $shippingAddress = $this->ShopOrders->ShippingAddresses->patchEntity($shippingAddress, $this->request->data);

            if (!$shippingAddress->errors()) {
                $this->cart->setShippingAddress($shippingAddress)->saveOrder();
            }
            $this->_redirectNext();

            /*
            if ($this->ShopOrders->ShippingAddresses->save($shippingAddress) && $this->cart->setShippingAddressById($shippingAddress->id)) {
                $this->Flash->success(__d('shop','Shipping information has been updated'));

                //$this->redirect(['action' => 'shipping']);
                $this->_redirectNext();
            } else {
                $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            }
            */
        }

        $shippingMethods = Configure::read('Shop.ShippingMethods');
        $shippingOptions = [];
        array_walk($shippingMethods, function($val, $idx) use (&$shippingOptions) {
            $shippingOptions[$idx] = $val['name'];
        });

        if ($this->cart->customer->id) {
            $shippingAddresses = $this->ShopOrders->ShippingAddresses
                ->find()
                ->where(['shop_customer_id' => $this->cart->customer->id])
                ->all()
                ->toArray();
        }

        $this->set('shippingMethods', $shippingMethods);
        $this->set('shippingOptions', $shippingOptions);

        $this->set('shippingAddress', $shippingAddress);
        $this->set('shippingAddresses', $shippingAddresses);

    }

    public function payment()
    {

        if ($this->request->is(['put', 'post'])) {
            //$this->cart->order = $this->ShopOrders->patchEntity($this->cart->order, $this->request->data);
            $this->cart->patchOrderPayment($this->request->data);
            if (!$this->cart->order->errors() && $this->cart->saveOrder()) {
                $this->Flash->success(__d('shop','Payment information has been updated'));
                $this->_redirectNext();
            } else {
                $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            }
        }


        $paymentMethods = [];
        $paymentOptions = [];
        $cart =& $this->cart;

        $configuredPaymentMethods = Configure::read('Shop.PaymentMethods');
        array_walk($configuredPaymentMethods, function($val, $idx) use (&$paymentOptions, &$cart) {

            if ($cart->customer->is_guest && $val['guest'] !== true) {
                return false;
            }

            $orderCount = $this->ShopOrders->find()->where(['shop_customer_id' => $cart->customer->id, 'is_temporary' => false])->count();
            if ($orderCount < 0 && $val['guest'] !== true) {
                return false;
            }

            $paymentOptions[$idx] = $val['name'];
            $paymentMethods[] = $idx;
        });

        $this->set('paymentMethods', $paymentMethods);
        $this->set('paymentOptions', $paymentOptions);
    }

    public function review()
    {
        $nextStep = $this->_getNextStep();

        if ($nextStep != 'review') {
            $this->_redirectNext();
        }
    }

    public function submit()
    {
        if ($this->request->is(['put', 'post'])) {
            if ($this->cart->submitOrder($this->request->data)) {
                $orderId = $this->cart->order->id;
                $this->cart = $this->_getCart();
                $this->cart->reset();

                $this->_resetCartSession();

                $this->Flash->success(__d('shop','Order has been submitted'));
                $this->redirect(['action' => 'success', $orderId]);
            } else {
                $this->Flash->error(__d('shop','Ups. Something went wrong. Please try again.'));
            }
        }

        $this->setAction('review');
    }

    public function success($orderId = null)
    {
        $order = $this->ShopOrders->get($orderId);

        //$this->redirect(['controller' => 'ShopOrders', 'action' => 'index', $order->uuid]);

        $this->set('orderKey', $order->uuid);
    }

}