<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

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
        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Invoice id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Action->execute();
    }

    /**
     * Add method
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopOrderInvoice = $this->ShopOrderInvoices->newEmptyEntity();
        if ($this->request->is('post')) {
            $shopOrderInvoice = $this->ShopOrderInvoices->patchEntity($shopOrderInvoice, $this->request->getData());
            if ($this->ShopOrderInvoices->save($shopOrderInvoice)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order invoice')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order invoice')));
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
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderInvoice = $this->ShopOrderInvoices->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderInvoice = $this->ShopOrderInvoices->patchEntity($shopOrderInvoice, $this->request->getData());
            if ($this->ShopOrderInvoices->save($shopOrderInvoice)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order invoice')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order invoice')));
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
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderInvoice = $this->ShopOrderInvoices->get($id);
        if ($this->ShopOrderInvoices->delete($shopOrderInvoice)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop order invoice')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop order invoice')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
