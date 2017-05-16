<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopOrderInvoices Controller
 *
 * @property \Shop\Model\Table\ShopOrderInvoicesTable $ShopOrderInvoices
 */
class ShopOrderInvoicesController extends AppController
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
     * @param string|null $id Shop Order Invoice id.
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
        $shopOrderInvoice = $this->ShopOrderInvoices->newEntity();
        if ($this->request->is('post')) {
            $shopOrderInvoice = $this->ShopOrderInvoices->patchEntity($shopOrderInvoice, $this->request->data);
            if ($this->ShopOrderInvoices->save($shopOrderInvoice)) {
                $this->Flash->success(__('The {0} has been saved.', __('shop order invoice')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('shop order invoice')));
            }
        }
        $parentShopOrderInvoices = $this->ShopOrderInvoices->ParentShopOrderInvoices->find('list', ['limit' => 200]);
        $shopOrders = $this->ShopOrderInvoices->ShopOrders->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderInvoice', 'parentShopOrderInvoices', 'shopOrders'));
        $this->set('_serialize', ['shopOrderInvoice']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Order Invoice id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderInvoice = $this->ShopOrderInvoices->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderInvoice = $this->ShopOrderInvoices->patchEntity($shopOrderInvoice, $this->request->data);
            if ($this->ShopOrderInvoices->save($shopOrderInvoice)) {
                $this->Flash->success(__('The {0} has been saved.', __('shop order invoice')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('shop order invoice')));
            }
        }
        $parentShopOrderInvoices = $this->ShopOrderInvoices->ParentShopOrderInvoices->find('list', ['limit' => 200]);
        $shopOrders = $this->ShopOrderInvoices->ShopOrders->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderInvoice', 'parentShopOrderInvoices', 'shopOrders'));
        $this->set('_serialize', ['shopOrderInvoice']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order Invoice id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderInvoice = $this->ShopOrderInvoices->get($id);
        if ($this->ShopOrderInvoices->delete($shopOrderInvoice)) {
            $this->Flash->success(__('The {0} has been deleted.', __('shop order invoice')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('shop order invoice')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
