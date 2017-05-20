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
            'conditions' => []
        ];

        if ($this->request->query('order_id')) {
            $this->paginate['conditions']['shop_order_id'] = $this->request->query('order_id');
        }

        $this->set('fields.whitelist', true);
        $this->set('fields', [
            /*
            'shop_order.id' => [
                'formatter' => function($val, $row, $args, $view) {
                    return $view->Html->link($val, ['controller' => 'ShopOrders', 'action' => 'view', $row->id]);
                }
            ],
            */
            'id' => [
                'formatter' => function($val, $row, $args, $view) {
                    return $view->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'product_sku' => [
                'formatter' => function($val, $row) {
                    return ($val) ?: $row->getProduct()->getSku();
                }
            ],
            'product_title' => [
                'formatter' => function($val, $row, $args, $view) {
                    $val = ($val) ?: $row->getProduct()->getTitle();
                    return $view->Html->link($val, $row->getProduct()->getAdminUrl(), ['class' => 'link-modal-frame']);
                }
            ],
            'amount' => [],
            /*
            'value_tax' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            'value_net' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            */
            'value' => ['title' => __d('shop','Total'), 'formatter' => function($val, $row, $args, $view) {
                $val = ($val) ?: $row->value_net + $row->value_tax;
                return $view->Number->currency($val, $row->currency);
            }]
        ]);
        $this->set('rowActions', [
            [__('View'), ['action' => 'view', ':id']]
        ]);

        $this->Backend->executeAction();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order Item id.
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
        $shopOrderItem = $this->ShopOrderItems->newEntity();
        if ($this->request->is('post')) {
            $shopOrderItem = $this->ShopOrderItems->patchEntity($shopOrderItem, $this->request->data);
            if ($this->ShopOrderItems->save($shopOrderItem)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop order item')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop order item')));
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
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrderItem = $this->ShopOrderItems->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrderItem = $this->ShopOrderItems->patchEntity($shopOrderItem, $this->request->data);
            if ($this->ShopOrderItems->save($shopOrderItem)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop order item')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop order item')));
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
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrderItem = $this->ShopOrderItems->get($id);
        if ($this->ShopOrderItems->delete($shopOrderItem)) {
            $this->Flash->success(__d('shop','The {0} has been deleted.', __d('shop','shop order item')));
        } else {
            $this->Flash->error(__d('shop','The {0} could not be deleted. Please, try again.', __d('shop','shop order item')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
