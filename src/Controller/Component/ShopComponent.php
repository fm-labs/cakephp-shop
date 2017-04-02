<?php

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopCustomer;

class ShopComponent extends Component
{

    public $components = ['Shop.Cart', 'Shop.Checkout'];

    /**
     * @var ShopCustomer
     */
    public $customer;

    public function initialize(array $config) {

        $defaultLayout = Configure::read('Shop.Layout.default');
        if ($defaultLayout) {
            $this->_registry->getController()->viewBuilder()->layout($defaultLayout);
        }
    }

    public function beforeFilter(Event $event)
    {
        $this->customer = $this->request->session()->read('Shop.Customer');

        // check customer
        if (!$this->customer && $this->request->session()->check('Auth.User.id')) {
            // user login detected
            $userId = $this->request->session()->read('Auth.User.id');
            $customer = TableRegistry::get('Shop.ShopCustomers')->find()
                ->where(['ShopCustomers.user_id' => $userId])
                ->first();

            if (!$customer) {
                $customer = TableRegistry::get('Shop.ShopCustomers')->createFromUserId($userId);
            }

            if ($customer) {
                $this->setCustomer($customer);
            } else {
                Log::alert('User ' . $userId . ' has no shop customer');
                $this->resetCustomer();
                $this->Checkout->reset();
            }

        }
        elseif ($this->customer && $this->customer->user_id && !$this->request->session()->check('Auth.User.id')) {
            // user logout detected
            $this->resetCustomer();
            $event->subject()->request->session()->write('Shop.Customer', null);
            $this->Cart->reset();
            $this->Checkout->reset();
        }
    }

    public function beforeRender(Event $event)
    {
        $event->subject()->request->session()->write('Shop.Customer', $this->getCustomer());
        $event->subject()->set('customer', $this->getCustomer());
    }

    public function setCustomer(ShopCustomer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function resetCustomer()
    {
        $this->customer = null;
        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}