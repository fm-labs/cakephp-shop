<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopOrderAddresses Controller
 *
 * @property \Shop\Model\Table\ShopOrderAddressesTable $ShopOrderAddresses
 */
class ShopOrderAddressesController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopOrders', 'ShopCustomerAddresses', 'Countries'],
        ];

        $filter = $this->request->getQuery();
        if (isset($filter['_'])) {
            unset($filter['_']);
        }
        //if ($filter) {
        //    $this->paginate['conditions'] = $filter;
        //}

        if (isset($filter['shop_order_id'])) {
            $this->paginate['conditions']['ShopOrderAddresses.shop_order_id'] = (int)$filter['shop_order_id'];
        }
        $this->set('shopOrderAddresses', $this->paginate($this->ShopOrderAddresses));
        $this->set('_serialize', ['shopOrderAddresses']);
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Address id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopOrderAddress = $this->ShopOrderAddresses->get($id, [
            'contain' => ['ShopOrders', 'ShopCustomerAddresses', 'Countries'],
        ]);
        $this->set('shopOrderAddress', $shopOrderAddress);
        $this->set('_serialize', ['shopOrderAddress']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopOrderAddress = $this->ShopOrderAddresses->newEntity();
        if ($this->request->is('post')) {
            $shopOrderAddress = $this->ShopOrderAddresses->patchEntity($shopOrderAddress, $this->request->getData());
            if ($this->ShopOrderAddresses->save($shopOrderAddress)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order address')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order address')));
            }
        }
        $shopOrders = $this->ShopOrderAddresses->ShopOrders->find('list', ['limit' => 200]);
        $shopCustomerAddresses = $this->ShopOrderAddresses->ShopCustomerAddresses->find('list', ['limit' => 200]);
        $countries = $this->ShopOrderAddresses->Countries->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderAddress', 'shopOrders', 'shopCustomerAddresses', 'countries'));
        $this->set('_serialize', ['shopOrderAddress']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Order Address id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderAddress = $this->ShopOrderAddresses->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderAddress = $this->ShopOrderAddresses->patchEntity($shopOrderAddress, $this->request->getData());
            if ($this->ShopOrderAddresses->save($shopOrderAddress)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order address')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order address')));
            }
        }
        $shopOrders = $this->ShopOrderAddresses->ShopOrders->find('list', ['limit' => 200]);
        $shopCustomerAddresses = $this->ShopOrderAddresses->ShopCustomerAddresses->find('list', ['limit' => 200]);
        $countries = $this->ShopOrderAddresses->Countries->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderAddress', 'shopOrders', 'shopCustomerAddresses', 'countries'));
        $this->set('_serialize', ['shopOrderAddress']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order Address id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderAddress = $this->ShopOrderAddresses->get($id);
        if ($this->ShopOrderAddresses->delete($shopOrderAddress)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop order address')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop order address')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
