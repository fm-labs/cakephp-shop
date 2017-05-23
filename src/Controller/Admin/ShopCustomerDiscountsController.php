<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopCustomerDiscounts Controller
 *
 * @property \Shop\Model\Table\ShopCustomerDiscountsTable $ShopCustomerDiscounts
 */
class ShopCustomerDiscountsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers', 'ShopProducts']
        ];

        $this->set('fields', [
            'id' => [],
            'shop_customer' => ['formatter' => function($val, $row, $args, $view) {
                return ($val) ? $val->display_name : null;
            }],
            'shop_product_id' => ['formatter' => function($val, $row, $args, $view) {
                return ($val) ? $row->shop_product->title : null;
            }],
            'type' => [],
            'valuetype' => [],
            'value' => [],

        ]);
        $this->set('fields.whitelist', true);
        $this->Backend->executeAction();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Customer Discount id.
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
        $shopCustomerDiscount = $this->ShopCustomerDiscounts->newEntity();
        if ($this->request->is('post')) {
            $shopCustomerDiscount = $this->ShopCustomerDiscounts->patchEntity($shopCustomerDiscount, $this->request->data);
            if ($this->ShopCustomerDiscounts->save($shopCustomerDiscount)) {
                $this->Flash->success(__('The {0} has been saved.', __('shop customer discount')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('shop customer discount')));
            }
        }
        $shopCustomers = $this->ShopCustomerDiscounts->ShopCustomers->find('list', ['limit' => 200]);
        $shopProducts = $this->ShopCustomerDiscounts->ShopProducts->find('list', ['limit' => 200]);
        $this->set(compact('shopCustomerDiscount', 'shopCustomers', 'shopProducts'));
        $this->set('_serialize', ['shopCustomerDiscount']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Customer Discount id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopCustomerDiscount = $this->ShopCustomerDiscounts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCustomerDiscount = $this->ShopCustomerDiscounts->patchEntity($shopCustomerDiscount, $this->request->data);
            if ($this->ShopCustomerDiscounts->save($shopCustomerDiscount)) {
                $this->Flash->success(__('The {0} has been saved.', __('shop customer discount')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', __('shop customer discount')));
            }
        }
        $shopCustomers = $this->ShopCustomerDiscounts->ShopCustomers->find('list', ['limit' => 200]);
        $shopProducts = $this->ShopCustomerDiscounts->ShopProducts->find('list', ['limit' => 200]);
        $this->set(compact('shopCustomerDiscount', 'shopCustomers', 'shopProducts'));
        $this->set('_serialize', ['shopCustomerDiscount']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Customer Discount id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopCustomerDiscount = $this->ShopCustomerDiscounts->get($id);
        if ($this->ShopCustomerDiscounts->delete($shopCustomerDiscount)) {
            $this->Flash->success(__('The {0} has been deleted.', __('shop customer discount')));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', __('shop customer discount')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
