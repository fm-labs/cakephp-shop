<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopOrderTransactionNotifies Controller
 *
 * @property \Shop\Model\Table\ShopOrderTransactionNotifiesTable $ShopOrderTransactionNotifies
 */
class ShopOrderTransactionNotifiesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Transaction Notify id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Action->execute();
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopOrderTransactionNotify = $this->ShopOrderTransactionNotifies->newEntity();
        if ($this->request->is('post')) {
            $shopOrderTransactionNotify = $this->ShopOrderTransactionNotifies->patchEntity($shopOrderTransactionNotify, $this->request->data);
            if ($this->ShopOrderTransactionNotifies->save($shopOrderTransactionNotify)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order transaction notify')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order transaction notify')));
            }
        }
        $shopOrderTransactions = $this->ShopOrderTransactionNotifies->ShopOrderTransactions->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderTransactionNotify', 'shopOrderTransactions'));
        $this->set('_serialize', ['shopOrderTransactionNotify']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Order Transaction Notify id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderTransactionNotify = $this->ShopOrderTransactionNotifies->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderTransactionNotify = $this->ShopOrderTransactionNotifies->patchEntity($shopOrderTransactionNotify, $this->request->data);
            if ($this->ShopOrderTransactionNotifies->save($shopOrderTransactionNotify)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order transaction notify')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order transaction notify')));
            }
        }
        $shopOrderTransactions = $this->ShopOrderTransactionNotifies->ShopOrderTransactions->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderTransactionNotify', 'shopOrderTransactions'));
        $this->set('_serialize', ['shopOrderTransactionNotify']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order Transaction Notify id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderTransactionNotify = $this->ShopOrderTransactionNotifies->get($id);
        if ($this->ShopOrderTransactionNotifies->delete($shopOrderTransactionNotify)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop order transaction notify')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop order transaction notify')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
