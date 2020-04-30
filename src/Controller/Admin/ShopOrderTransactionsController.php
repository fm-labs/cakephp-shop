<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

/**
 * ShopOrderTransactions Controller
 *
 * @property \Shop\Model\Table\ShopOrderTransactionsTable $ShopOrderTransactions
 */
class ShopOrderTransactionsController extends AppController
{
    public $actions = [
        'index' => 'Admin.Index',
        'view' => 'Admin.View',
    ];

    public $paginate = [
        'order' => ['ShopOrderTransactions.id' => 'DESC'],
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $dataUrl = ['rows' => 1];
        $query = $this->ShopOrderTransactions->find('all', ['status' => true, 'contain' => ['ShopOrders']]);
        if ($this->request->getQuery('shop_order_id')) {
            $dataUrl['shop_order_id'] = $this->request->getQuery('shop_order_id');
            $query->where(['ShopOrderTransactions.shop_order_id' => $this->request->getQuery('shop_order_id')]);
        }

        $this->set('fields', [
            'shop_order' => ['formatter' => ['related', 'nr_formatted']],
            'type', 'engine', 'currency_code',
            'value' => ['formatter' => ['currency', ['currency_field' => 'currency_code']]],
            'status' => ['formatter' => 'status'],
            'ext_status', 'last_message', 'is_test',
        ]);
        $this->set('fields.whitelist', ['shop_order', 'type', 'engine', 'currency_code', 'value', 'status', 'ext_status', 'last_message', 'is_test']);
        $this->set('ajax', $dataUrl);
        $this->set('queryObj', $query);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Transaction id.
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
        $shopOrderTransaction = $this->ShopOrderTransactions->newEmptyEntity();
        if ($this->request->is('post')) {
            $shopOrderTransaction = $this->ShopOrderTransactions->patchEntity($shopOrderTransaction, $this->request->getData());
            if ($this->ShopOrderTransactions->save($shopOrderTransaction)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order transaction')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order transaction')));
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
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderTransaction = $this->ShopOrderTransactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderTransaction = $this->ShopOrderTransactions->patchEntity($shopOrderTransaction, $this->request->getData());
            if ($this->ShopOrderTransactions->save($shopOrderTransaction)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order transaction')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order transaction')));
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
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderTransaction = $this->ShopOrderTransactions->get($id);
        if ($this->ShopOrderTransactions->delete($shopOrderTransaction)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop order transaction')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop order transaction')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
