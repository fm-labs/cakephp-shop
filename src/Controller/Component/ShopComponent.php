<?php

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\ResultSet;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopCustomer;
use Shop\Model\Table\ShopCustomersTable;

/**
 * Class ShopComponent
 * @package Shop\Controller\Component
 */
class ShopComponent extends Component
{
    public $components  = ['Auth'];

    /**
     * @var ShopCustomer
     */
    protected $_customer;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        $defaultLayout = Configure::read('Shop.Layout.default');
        if ($defaultLayout) {
            $this->_registry->getController()->viewBuilder()->setLayout($defaultLayout);
        }
    }

    /**
     * @param Event $event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->_loadCustomer();
    }

    /**
     * Load customer entity
     * @return void
     */
    protected function _loadCustomer()
    {
        if ($this->Auth->user()) {
            /** @var ShopCustomersTable $ShopCustomers */
            $ShopCustomers = TableRegistry::getTableLocator()->get('Shop.ShopCustomers');
            $customer = $ShopCustomers->createFromUserId($this->Auth->user('id'));
            $this->setCustomer($customer);
        }
    }

    /**
     * @param Event $event
     */
    public function beforeRender(Event $event)
    {
        $event->getSubject()->set('customer', $this->getCustomer());
    }

    /**
     * @param null $field
     * @return mixed|null|ShopCustomer
     */
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

    /**
     * @return null|ShopCustomer
     */
    public function getCustomer()
    {
        return $this->customer(null);
    }

    /**
     * @return null|int
     */
    public function getCustomerId()
    {
        return $this->customer('id');
    }

    /**
     * @param ShopCustomer $customer
     * @return $this
     */
    public function setCustomer(ShopCustomer $customer)
    {
        $this->_customer = $customer;
        $this->request->getSession()->write('Shop.Customer', $this->_customer->toArray());

        return $this;
    }

    /**
     * @return $this
     */
    public function resetCustomer()
    {
        $this->_customer = null;
        $this->request->getSession()->delete('Shop.Customer');

        return $this;
    }

    /**
     * @return array
     */
    public function getCountriesList()
    {
        $countries = TableRegistry::getTableLocator()->get('Shop.ShopCountries')
            ->find('list')
            ->find('published')
            ->order(['name_de' => 'ASC'])
            ->toArray();

        return $countries;
    }

    /**
     * @return ResultSet
     */
    public function getCustomerAddresses()
    {
        $addresses = null;
        if ($this->_customer && !$this->customer('is_guest')) {
            $addresses = TableRegistry::getTableLocator()->get('Shop.ShopCustomerAddresses')
                ->find()
                ->where(['ShopCustomerAddresses.shop_customer_id' => $this->getCustomerId()]);
        }

        return $addresses;
    }

    /**
     * @return array
     */
    public function getCustomerAddressesList()
    {
        $list = [];
        $addresses = $this->getCustomerAddresses();
        if ($addresses) {
            $addresses->each(function ($address) use (&$list) {
                $list[$address->id] = $address->oneline;
            });
        }

        return $list;
    }
}
