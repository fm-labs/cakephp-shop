<?php

namespace Shop\Controller;

use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Shop\Model\Table\ShopCustomerAddressesTable;

/**
 * Class CustomerAddressesController
 * @package Shop\Controller
 *
 * @property ShopCustomerAddressesTable $ShopCustomerAddresses
 */
class CustomerAddressesController extends AppController
{
    public $modelClass = "Shop.ShopCustomerAddresses";

    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny([]);
    }

    public function index()
    {
        $this->paginate = [
            'conditions' => ['ShopCustomerAddresses.shop_customer_id' => $this->Shop->customer('id')],
            'contain' => ['Countries']
        ];
        $addresses = $this->paginate($this->ShopCustomerAddresses);
        $this->set(compact('addresses'));
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new BadRequestException();
        }
        $address = $this->ShopCustomerAddresses->find()
            ->where(['ShopCustomerAddresses.id' => $id, 'ShopCustomerAddresses.shop_customer_id' => $this->Shop->customer('id')])
            ->contain(['Countries'])
            ->first();

        if (!$address) {
            throw new NotFoundException();
        }

        if ($this->request->is(['put', 'post'])) {
            $address = $this->ShopCustomerAddresses->patchEntity($address, $this->request->data);
            if ($this->ShopCustomerAddresses->save($address)) {
                $this->Flash->success(__d('shop','Saved'));
            } else {
                $this->Flash->error(__d('shop','Please fill all required fields'));
            }
        }

        $countries = $this->ShopCustomerAddresses->Countries->find('list')->find('published')->toArray();
        $this->set(compact('address', 'countries'));
    }
}