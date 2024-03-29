<?php
declare(strict_types=1);

namespace Shop\Controller;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\CellTrait;

/**
 * Class CustomerAddressesController
 * @package Shop\Controller
 *
 * @property \Shop\Model\Table\ShopCustomerAddressesTable $ShopCustomerAddresses
 */
class CustomerAddressesController extends AppController
{
    public $modelClass = "Shop.ShopCustomerAddresses";

    use CellTrait;

    public function index()
    {
        $this->paginate = [
            'conditions' => ['ShopCustomerAddresses.shop_customer_id' => $this->Shop->customer('id')],
            'contain' => ['Countries'],
        ];
        $addresses = $this->paginate($this->ShopCustomerAddresses);
        $this->set(compact('addresses'));
    }

    public function add()
    {
        $address = $this->ShopCustomerAddresses->newEntity(
            $this->Shop->customer()->extract(['first_name', 'last_name', 'email']),
            ['validate' => false]
        );

        if ($this->request->is(['put', 'post'])) {
            $address = $this->ShopCustomerAddresses->patchEntity($address, $this->request->getData());
            $address->shop_customer_id = $this->Shop->getCustomerId();
            if ($this->ShopCustomerAddresses->save($address)) {
                $this->Flash->success(__d('shop', 'Saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'Please fill all required fields'));
            }
        }
        $this->set(compact('address'));
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
            $address = $this->ShopCustomerAddresses->patchEntity($address, $this->request->getData());
            $address->shop_customer_id = $this->Shop->getCustomerId();
            if ($this->ShopCustomerAddresses->save($address)) {
                $this->Flash->success(__d('shop', 'Saved'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'Please fill all required fields'));
            }
        }

        $countries = $this->ShopCustomerAddresses->Countries->find('list')->find('published')->toArray();
        $this->set(compact('address', 'countries'));
    }
}
