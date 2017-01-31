<?php
namespace Shop\Controller;

use Cake\Core\Configure;

/**
 * ShopOrders Controller
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
class OrdersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow();
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $customerId = $this->request->session()->read('Shop.Customer.id');
        $this->paginate = [
            'contain' => ['ShopCustomers'],
            'conditions' => ['ShopOrders.is_temporary' => false, 'ShopOrders.shop_customer_id' => $customerId],
            'order' => ['ShopOrders.id' => 'DESC']
        ];
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
            'contain' => ['ShopCustomers', 'ShopOrderItems', 'BillingAddress', 'ShippingAddress']
        ]);
        $this->set('shopOrder', $shopOrder);
        $this->set('_serialize', ['shopOrder']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Order id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function cancel($id = null)
    {
        /*
        $this->request->allowMethod(['post', 'delete']);
        $shopOrder = $this->ShopOrders->get($id);
        if ($this->ShopOrders->delete($shopOrder)) {
            $this->Flash->success(__d('shop','The {0} has been deleted.', __d('shop','shop order')));
        } else {
            $this->Flash->error(__d('shop','The {0} could not be deleted. Please, try again.', __d('shop','shop order')));
        }
        return $this->redirect(['action' => 'index']);
        */
        $this->Flash->error(__d('shop','The {0} could not be cancled. Please, try again.', __d('shop','shop order')));
    }

}
