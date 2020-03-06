<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopOrderItems Controller
 *
 * @property \Shop\Model\Table\ShopOrderItemsTable $ShopOrderItems
 */
class ShopOrderItemsController extends AppController
{

    /**
     * @var array
     */
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
        $this->paginate = [
            'contain' => ['ShopOrders'],
        ];

        $this->helpers['Banana.Status'] = [];

        $dataUrl = ['rows' => 1];
        $query = $this->ShopOrderItems->find();
        if ($this->request->getQuery('order_id')) {
            $dataUrl['order_id'] = $this->request->getQuery('order_id');
            $query->where(['shop_order_id' => $this->request->getQuery('order_id')]);
        }

        $this->set('ajax', $dataUrl);
        $this->set('paginate', true);
        $this->set('fields.whitelist', true);
        $this->set('fields', [
            /*
            'shop_order.id' => [
                'formatter' => function($val, $row, $args, $view) {
                    return $view->Html->link($val, ['controller' => 'ShopOrders', 'action' => 'view', $row->id]);
                }
            ],
            */
            'id' => [],
            'sku' => [],
            'title' => [],
            'amount' => [],
            /*
            'value_tax' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            'value_net' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            */
            'value_total' => [],
            'is_processed' => [],
            //'_status' => ['formatter' => function($val, $row, $args, $view) {
            //    return $view->Status->label($val);
            //}],
            'last_message' => []
        ]);
        $this->set('rowActions', [
            [__d('shop', 'View'), ['action' => 'view', ':id']]
        ]);
        $this->set('queryObj', $query);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Item id.
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
        $shopOrderItem = $this->ShopOrderItems->newEntity();
        if ($this->request->is('post')) {
            $shopOrderItem = $this->ShopOrderItems->patchEntity($shopOrderItem, $this->request->data);
            if ($this->ShopOrderItems->save($shopOrderItem)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order item')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order item')));
            }
        }
        $shopOrders = $this->ShopOrderItems->ShopOrders->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderItem', 'shopOrders'));
        $this->set('_serialize', ['shopOrderItem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Order Item id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderItem = $this->ShopOrderItems->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderItem = $this->ShopOrderItems->patchEntity($shopOrderItem, $this->request->data);
            if ($this->ShopOrderItems->save($shopOrderItem)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop order item')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop order item')));
            }
        }
        $shopOrders = $this->ShopOrderItems->ShopOrders->find('list', ['limit' => 200]);
        $this->set(compact('shopOrderItem', 'shopOrders'));
        $this->set('_serialize', ['shopOrderItem']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order Item id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderItem = $this->ShopOrderItems->get($id);
        if ($this->ShopOrderItems->delete($shopOrderItem)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop order item')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop order item')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
