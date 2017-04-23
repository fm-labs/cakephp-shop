<?php
namespace Shop\Controller\Admin;

use Cake\Core\Configure;
use Shop\Controller\Admin\AppController;

/**
 * ShopOrders Controller
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
class ShopOrdersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers', 'BillingAddress', 'ShippingAddress'],
            'conditions' => ['ShopOrders.is_temporary' => false],
            'order' => ['ShopOrders.id' => 'DESC']
        ];

        $filter = $this->request->query;
        if (isset($filter['_'])) unset($filter['_']);
        //if ($filter) {
        //    $this->paginate['conditions'] = $filter;
        //}

        if (isset($filter['shop_customer_id'])) {
            $this->paginate['conditions']['ShopOrders.shop_customer_id'] = (int) $filter['shop_customer_id'];
        }

        $this->set('shopOrders', $this->paginate($this->ShopOrders));
        $this->set('_serialize', ['shopOrders']);
    }

    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => ['ShopCustomers', 'ShopCarts', 'ShopOrderItems', 'BillingAddress' => ['Countries'], 'ShippingAddress' => ['Countries']]
        ]);
        $this->set('shopOrder', $shopOrder);
        $this->set('_serialize', ['shopOrder']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopOrder = $this->ShopOrders->newEntity();
        if ($this->request->is('post')) {
            $shopOrder = $this->ShopOrders->patchEntity($shopOrder, $this->request->data);
            if ($this->ShopOrders->save($shopOrder)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop order')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop order')));
            }
        }
        $shopCustomers = $this->ShopOrders->ShopCustomers->find('list', ['limit' => 200]);
        $billingAddresses = $this->ShopOrders->ShopCustomerAddresses->find('list', ['limit' => 200])->toArray();
        $shippingAddresses = $this->ShopOrders->ShopCustomerAddresses->find('list', ['limit' => 200])->toArray();
        $this->set(compact('shopOrder', 'shopCustomers', 'billingAddresses', 'shippingAddresses'));
        $this->set('_serialize', ['shopOrder']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Order id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopOrder = $this->ShopOrders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopOrder = $this->ShopOrders->patchEntity($shopOrder, $this->request->data);
            if ($this->ShopOrders->save($shopOrder)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','shop order')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','shop order')));
            }
        }
        //$billingAddresses = $this->ShopOrders->BillingAddresses->find('list', ['limit' => 200])->where(['BillingAddresses.shop_customer_id' => $shopOrder->shop_customer_id])->toArray();
        //$shippingAddresses = $this->ShopOrders->ShippingAddresses->find('list', ['limit' => 200])->toArray();
        $this->set(compact('shopOrder', 'shopCustomers' /*, 'billingAddresses', 'shippingAddresses' */));
        $this->set('_serialize', ['shopOrder']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopOrder = $this->ShopOrders->get($id);
        if ($this->ShopOrders->delete($shopOrder)) {
            $this->Flash->success(__d('shop','The {0} has been deleted.', __d('shop','shop order')));
        } else {
            $this->Flash->error(__d('shop','The {0} could not be deleted. Please, try again.', __d('shop','shop order')));
        }
        return $this->redirect(['action' => 'index']);
    }


    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function emailOwnerOrderNotify($id = null)
    {
        if ($debug = $this->ShopOrders->emailOwnerOrderNotify($id)) {
            $this->Flash->success(__d('shop','The notification has been sent.'));
        } else {
            $this->Flash->error(__d('shop','The notification could not be sent.'));
        };

        if (Configure::read('debug')) {
            $this->autoRender = false;
        } else {
            $this->redirect($this->referer(['action' => 'index']));
        }
    }


    /**
     * View method
     *
     * @param string|null $id Shop Order id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function calculate($id = null)
    {
        if ($debug = $this->ShopOrders->calculate($id)) {
            $this->Flash->success('OK');
        } else {
            $this->Flash->error('FAILED');
        };
        //$this->autoRender = false;
        $this->redirect($this->referer(['action' => 'edit', $id]));
    }

}
