<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopOrderTransactions Controller
 *
 * @property \Shop\Model\Table\ShopOrderTransactionsTable $ShopOrderTransactions
 */
class ShopOrderTransactionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->Backend->executeAction();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Transaction id.
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
        $shopOrderTransaction = $this->ShopOrderTransactions->newEntity();
        if ($this->request->is('post')) {
            $shopOrderTransaction = $this->ShopOrderTransactions->patchEntity($shopOrderTransaction, $this->request->data);
            if ($this->ShopOrderTransactions->save($shopOrderTransaction)) {
                $this->Flash->success(__('The {0} has been saved.', __('shop order transaction')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('shop order transaction')));
            }
        }
        $shopOrders = $this->ShopOrderTransactions->ShopOrders->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderTransaction', 'shopOrders'));
        $this->set('_serialize', ['shopOrderTransaction']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Order Transaction id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderTransaction = $this->ShopOrderTransactions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderTransaction = $this->ShopOrderTransactions->patchEntity($shopOrderTransaction, $this->request->data);
            if ($this->ShopOrderTransactions->save($shopOrderTransaction)) {
                $this->Flash->success(__('The {0} has been saved.', __('shop order transaction')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('shop order transaction')));
            }
        }
        $shopOrders = $this->ShopOrderTransactions->ShopOrders->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderTransaction', 'shopOrders'));
        $this->set('_serialize', ['shopOrderTransaction']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order Transaction id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderTransaction = $this->ShopOrderTransactions->get($id);
        if ($this->ShopOrderTransactions->delete($shopOrderTransaction)) {
            $this->Flash->success(__('The {0} has been deleted.', __('shop order transaction')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('shop order transaction')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
