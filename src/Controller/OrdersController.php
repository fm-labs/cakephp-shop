<?php
namespace Shop\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;

/**
 * ShopOrders Controller
 *
 * @property \Shop\Model\Table\ShopOrdersTable $ShopOrders
 */
class OrdersController extends AppController
{

    public $modelClass = "Shop.ShopOrders";

    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny([]);
        $this->Auth->allow(['view']);
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
    public function view($uuid = null)
    {
        if (!$uuid) {
            throw new BadRequestException();
        }

        $shopOrder = $this->ShopOrders->find('order', compact('uuid'));
        if (!$shopOrder) {
            throw new NotFoundException();
        }

        if (!$this->Auth->user() || $this->Shop->getCustomerId() != $shopOrder->shop_customer_id) {
            $this->viewBuilder()->template('view_public');
        }

        $this->set('order', $shopOrder);
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
