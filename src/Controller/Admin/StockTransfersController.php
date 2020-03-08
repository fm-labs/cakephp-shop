<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * StockTransfers Controller
 *
 * @property \Shop\Model\Table\StockTransfersTable $StockTransfers
 */
class StockTransfersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentStockTransfers', 'ShopStocks', 'ShopProducts'],
        ];
        $this->set('stockTransfers', $this->paginate($this->StockTransfers));
        $this->set('_serialize', ['stockTransfers']);
    }

    /**
     * View method
     *
     * @param string|null $id Stock Transfer id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stockTransfer = $this->StockTransfers->get($id, [
            'contain' => ['ParentStockTransfers', 'ShopStocks', 'ShopProducts', 'ChildStockTransfers'],
        ]);
        $this->set('stockTransfer', $stockTransfer);
        $this->set('_serialize', ['stockTransfer']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stockTransfer = $this->StockTransfers->newEntity();
        if ($this->request->is('post')) {
            $stockTransfer = $this->StockTransfers->patchEntity($stockTransfer, $this->request->data);
            if ($this->StockTransfers->save($stockTransfer)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'stock transfer')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'stock transfer')));
            }
        }
        $parentStockTransfers = $this->StockTransfers->ParentStockTransfers->find('list', ['limit' => 200]);
        $shopStocks = $this->StockTransfers->ShopStocks->find('list', ['limit' => 200]);
        $shopProducts = $this->StockTransfers->ShopProducts->find('list', ['limit' => 200]);
        $this->set(compact('stockTransfer', 'parentStockTransfers', 'shopStocks', 'shopProducts'));
        $this->set('_serialize', ['stockTransfer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock Transfer id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stockTransfer = $this->StockTransfers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stockTransfer = $this->StockTransfers->patchEntity($stockTransfer, $this->request->data);
            if ($this->StockTransfers->save($stockTransfer)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'stock transfer')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'stock transfer')));
            }
        }
        $parentStockTransfers = $this->StockTransfers->ParentStockTransfers->find('list', ['limit' => 200]);
        $shopStocks = $this->StockTransfers->ShopStocks->find('list', ['limit' => 200]);
        $shopProducts = $this->StockTransfers->ShopProducts->find('list', ['limit' => 200]);
        $this->set(compact('stockTransfer', 'parentStockTransfers', 'shopStocks', 'shopProducts'));
        $this->set('_serialize', ['stockTransfer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock Transfer id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stockTransfer = $this->StockTransfers->get($id);
        if ($this->StockTransfers->delete($stockTransfer)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'stock transfer')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'stock transfer')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
