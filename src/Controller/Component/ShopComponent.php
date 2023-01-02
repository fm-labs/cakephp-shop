<?php
declare(strict_types=1);

namespace Shop\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopCustomer;

/**
 * Class ShopComponent
 * @package Shop\Controller\Component
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class ShopComponent extends Component
{
    public $components  = ['Authentication'];

    /**
     * @var \Shop\Model\Entity\ShopCustomer
     */
    protected $_customer;

    /**
     * @param array $config
     */
    public function initialize(array $config): void
    {
        $defaultLayout = Configure::read('Shop.Layout.default');
        if ($defaultLayout) {
            $this->getController()->viewBuilder()->setLayout($defaultLayout);
        }
        $this->getController()->viewBuilder()->addHelper('User.Auth');
    }

    /**
     * @param \Cake\Event\Event $event
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->_loadCustomer();
    }

    /**
     * Load customer entity
     * @return void
     */
    protected function _loadCustomer()
    {
        if ($this->Authentication->getIdentity()) {
            /** @var \Shop\Model\Table\ShopCustomersTable $ShopCustomers */
            $ShopCustomers = TableRegistry::getTableLocator()->get('Shop.ShopCustomers');
            $customer = $ShopCustomers->createFromUserId($this->Authentication->getIdentityData('id'));
            $this->setCustomer($customer);
        }
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
        $event->getSubject()->set('customer', $this->getCustomer());
    }

    /**
     * @param null $field
     * @return mixed|null|\Shop\Model\Entity\ShopCustomer
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
     * @return null|\Shop\Model\Entity\ShopCustomer
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
     * @param \Shop\Model\Entity\ShopCustomer $customer
     * @return $this
     */
    public function setCustomer(ShopCustomer $customer)
    {
        $this->_customer = $customer;
        $this->getController()->getRequest()->getSession()->write('Shop.Customer', $this->_customer->toArray());

        return $this;
    }

    /**
     * @return $this
     */
    public function resetCustomer()
    {
        $this->_customer = null;
        $this->getController()->getRequest()->getSession()->delete('Shop.Customer');

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
     * @return \Cake\ORM\Query|null
     * @todo Return array instead of Query?
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
            $addresses->all()->each(function ($address) use (&$list) {
                $list[$address->id] = $address->oneline;
            });
        }

        return $list;
    }
}
