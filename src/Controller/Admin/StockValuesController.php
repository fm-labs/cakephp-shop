<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * StockValues Controller
 *
 * @property \Shop\Model\Table\StockValuesTable $StockValues
 */
class StockValuesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopStocks', 'ShopProducts'],
        ];
        $this->set('stockValues', $this->paginate($this->StockValues));
        $this->set('_serialize', ['stockValues']);
    }

    /**
     * View method
     *
     * @param string|null $id Stock Value id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stockValue = $this->StockValues->get($id, [
            'contain' => ['ShopStocks', 'ShopProducts'],
        ]);
        $this->set('stockValue', $stockValue);
        $this->set('_serialize', ['stockValue']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stockValue = $this->StockValues->newEntity();
        if ($this->request->is('post')) {
            $stockValue = $this->StockValues->patchEntity($stockValue, $this->request->data);
            if ($this->StockValues->save($stockValue)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'stock value')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'stock value')));
            }
        }
        $shopStocks = $this->StockValues->ShopStocks->find('list', ['limit' => 200]);
        $shopProducts = $this->StockValues->ShopProducts->find('list', ['limit' => 200]);
        $this->set(compact('stockValue', 'shopStocks', 'shopProducts'));
        $this->set('_serialize', ['stockValue']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock Value id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stockValue = $this->StockValues->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stockValue = $this->StockValues->patchEntity($stockValue, $this->request->data);
            if ($this->StockValues->save($stockValue)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'stock value')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'stock value')));
            }
        }
        $shopStocks = $this->StockValues->ShopStocks->find('list', ['limit' => 200]);
        $shopProducts = $this->StockValues->ShopProducts->find('list', ['limit' => 200]);
        $this->set(compact('stockValue', 'shopStocks', 'shopProducts'));
        $this->set('_serialize', ['stockValue']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock Value id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stockValue = $this->StockValues->get($id);
        if ($this->StockValues->delete($stockValue)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'stock value')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'stock value')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
