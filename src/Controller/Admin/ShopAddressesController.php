<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopAddresses Controller
 *
 * @property \Shop\Model\Table\ShopAddressesTable $ShopAddresses
 */
class ShopAddressesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers']
        ];
        $this->set('shopAddresses', $this->paginate($this->ShopAddresses));
        $this->set('_serialize', ['shopAddresses']);
    }

    /**
     * View method
     *
     * @param string|null $id Shop Address id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopAddress = $this->ShopAddresses->get($id, [
            'contain' => ['ShopCustomers']
        ]);
        $this->set('shopAddress', $shopAddress);
        $this->set('_serialize', ['shopAddress']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopAddress = $this->ShopAddresses->newEntity();
        if ($this->request->is('post')) {
            $shopAddress = $this->ShopAddresses->patchEntity($shopAddress, $this->request->data);
            if ($this->ShopAddresses->save($shopAddress)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop address')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop address')));
            }
        }
        $shopCustomers = $this->ShopAddresses->ShopCustomers->find('list', ['limit' => 200]);
        $this->set(compact('shopAddress', 'shopCustomers'));
        $this->set('_serialize', ['shopAddress']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Address id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopAddress = $this->ShopAddresses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopAddress = $this->ShopAddresses->patchEntity($shopAddress, $this->request->data);
            if ($this->ShopAddresses->save($shopAddress)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop address')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop address')));
            }
        }
        $shopCustomers = $this->ShopAddresses->ShopCustomers->find('list', ['limit' => 200]);
        $this->set(compact('shopAddress', 'shopCustomers'));
        $this->set('_serialize', ['shopAddress']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Address id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopAddress = $this->ShopAddresses->get($id);
        if ($this->ShopAddresses->delete($shopAddress)) {
            $this->Flash->success(__d('shop','The {0} has been deleted.', __d('shop','shop address')));
        } else {
            $this->Flash->error(__d('shop','The {0} could not be deleted. Please, try again.', __d('shop','shop address')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
