<?php

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\ORM\ResultSet;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopCustomer;

/**
 * Class ShopComponent
 * @package Shop\Controller\Component
 */
class ShopComponent extends Component
{
    /**
     * @var ShopCustomer
     */
    protected $_customer;

    /**
     * @param array $config
     */
    public function initialize(array $config) {

        $defaultLayout = Configure::read('Shop.Layout.default');
        if ($defaultLayout) {
            $this->_registry->getController()->viewBuilder()->layout($defaultLayout);
        }
    }

    /**
     * @param Event $event
     */
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

    /**
     * @param Event $event
     */
    public function beforeRender(Event $event)
    {
        $event->subject()->set('customer', $this->getCustomer());
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
        $this->request->session()->write('Shop.Customer', $this->_customer->toArray());
        return $this;
    }

    /**
     * @return $this
     */
    public function resetCustomer()
    {
        $this->_customer = null;
        $this->request->session()->delete('Shop.Customer');
        return $this;
    }

    /**
     * @return array
     */
    public function getCountriesList()
    {
        $countries = TableRegistry::get('Shop.ShopCountries')
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
            $addresses = TableRegistry::get('Shop.ShopCustomerAddresses')
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
            $addresses->each(function($address) use (&$list) {
               $list[$address->id] = $address->oneline;
            });
        }
        return $list;
    }
}
