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
    }

    public function beforeRender(Event $event)
    {
        $event->subject()->set('customer', $this->getCustomer());
    }

    public function customer($field = null)
    {
        if ($field === null) {
            return $this->customer;
        }

        if (!$this->customer || !isset($this->customer[$field])) {
            return null;
        }

        return $this->customer[$field];
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getCustomerId()
    {
        return ($this->customer) ? $this->customer['id'] : null;
    }

    public function setCustomer(ShopCustomer $customer)
    {
        $this->customer = $customer;
        $this->request->session()->write('Shop.Customer', $this->customer);
        return $this;
    }

    public function resetCustomer()
    {
        $this->customer = null;
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
        if ($this->customer && !$this->customer['is_guest']) {
            $addresses = TableRegistry::get('Shop.ShopCustomerAddresses')
                ->find('list')
                ->where(['ShopCustomerAddresses.shop_customer_id' => $this->customer['id']])
                ->toArray();
        }
        return $addresses;
    }

}