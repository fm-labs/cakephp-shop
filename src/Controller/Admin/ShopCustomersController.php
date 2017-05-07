<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopCustomers Controller
 *
 * @property \Shop\Model\Table\ShopCustomersTable $ShopCustomers
 */
class ShopCustomersController extends AppController
{
    public $paginate = [
        'limit' => 100,
        'order' => ['ShopCustomers.last_name' => 'ASC', 'ShopCustomers.first_name' => 'ASC']
    ];

    public $actions = [
        'index' => 'Backend.Index',
        'view' => 'Backend.View'
    ];


    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('fields.whitelist', ['id', 'email', 'display_name', 'user']);
        $this->set('fields.blacklist', ['password', 'created', 'modified']);
        $this->Backend->executeAction();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Customer id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Backend->executeAction();
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopCustomer = $this->ShopCustomers->newEntity();
        if ($this->request->is('post')) {
            $shopCustomer = $this->ShopCustomers->patchEntity($shopCustomer, $this->request->data);
            if ($this->ShopCustomers->save($shopCustomer)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop customer')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop customer')));
            }
        }
        $this->set(compact('shopCustomer'));
        $this->set('_serialize', ['shopCustomer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Customer id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopCustomer = $this->ShopCustomers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCustomer = $this->ShopCustomers->patchEntity($shopCustomer, $this->request->data);
            if ($this->ShopCustomers->save($shopCustomer)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop customer')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop customer')));
            }
        }
        $this->set(compact('shopCustomer'));
        $this->set('_serialize', ['shopCustomer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Customer id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopCustomer = $this->ShopCustomers->get($id);
        if ($this->ShopCustomers->delete($shopCustomer)) {
            $this->Flash->success(__d('shop','The {0} has been deleted.', __d('shop','shop customer')));
        } else {
            $this->Flash->error(__d('shop','The {0} could not be deleted. Please, try again.', __d('shop','shop customer')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
