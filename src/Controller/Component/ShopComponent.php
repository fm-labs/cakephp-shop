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
    /**
     * @var ShopCustomer
     */
    protected $_customer;

    public function initialize(array $config) {

        $defaultLayout = Configure::read('Shop.Layout.default');
        if ($defaultLayout) {
            $this->_registry->getController()->viewBuilder()->layout($defaultLayout);
        }
    }

    public function beforeFilter(Event $event)
    {
        if ($this->request->session()->check('Shop.Customer.id')) {
            $customerId = $this->request->session()->read('Shop.Customer.id');
            try {
                $this->_customer = TableRegistry::get('Shop.ShopCustomers')->get($customerId, ['contain' => []]);
            } catch (\Exception $ex) {
                Log::error('ShopComponent::beforeFilter: ' . $ex->getMessage());
            }
        }
    }

    public function beforeRender(Event $event)
    {
        $event->subject()->set('customer', $this->getCustomer());
    }

    public function customer($field = null)
    {
        if (!$this->_customer) {
            return null;
        }

        if ($field === null) {
            return $this->_customer;
        }

        return $this->_customer->get($field);
    }

    public function getCustomer()
    {
        return $this->customer(null);
    }

    public function getCustomerId()
    {
        return $this->customer('id');
    }

    public function setCustomer(ShopCustomer $customer)
    {
        $this->_customer = $customer;
        $this->request->session()->write('Shop.Customer', $this->_customer->toArray());
        return $this;
    }

    public function resetCustomer()
    {
        $this->_customer = null;
        $this->request->session()->delete('Shop.Customer');
        return $this;
    }

    public function getCountriesList()
    {
        $countries = TableRegistry::get('Shop.ShopCountries')
            ->find('list')
            ->find('published')
            ->order(['name_de' => 'ASC'])
            ->toArray();
        return $countries;
    }

    public function getCustomerAddressesList()
    {
        $addresses = [];
        if ($this->_customer && !$this->customer('is_guest')) {
            $addresses = TableRegistry::get('Shop.ShopCustomerAddresses')
                ->find('list')
                ->where(['ShopCustomerAddresses.shop_customer_id' => $this->getCustomerId()])
                ->toArray();
        }
        return $addresses;
    }

}