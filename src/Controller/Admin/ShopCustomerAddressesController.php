<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

/**
 * ShopCustomerAddresses Controller
 *
 * @property \Shop\Model\Table\ShopCustomerAddressesTable $ShopCustomerAddresses
 */
class ShopCustomerAddressesController extends AppController
{
    public $modelClass = "Shop.ShopCustomerAddresses";

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Admin.Index',
        'view'      => 'Admin.View',
        'add'       => 'Admin.Add',
        'edit'      => 'Admin.Edit',
        'delete'    => 'Admin.Delete',
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ShopCustomers'],
        ];

        $filter = $this->request->getQuery();
        if (isset($filter['_'])) {
            unset($filter['_']);
        }
        //if ($filter) {
        //    $this->paginate['conditions'] = $filter;
        //}

        if ($this->request->getQuery('shop_customer_id')) {
            $this->paginate['conditions']['ShopCustomerAddresses.shop_customer_id'] = (int)$filter['shop_customer_id'];
        }

        $this->set('fields', [
            'id' => [],
            'shop_customer' => ['formatter' => ['related', 'display_name'], 'type' => 'object'],
            'oneline' => [],
        ]);
        $this->set('fields.whitelist', ['id', 'shop_customer', 'oneline']);
        $this->set('paginate', true);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Address id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $entity = $this->ShopCustomerAddresses->get($id, ['contain' => ['ShopCustomers']]);
        $this->set('entity', $entity);
        $this->Action->execute();
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopCustomerAddress = $this->ShopCustomerAddresses->newEmptyEntity();
        if ($this->request->is('post')) {
            $shopCustomerAddress = $this->ShopCustomerAddresses->patchEntity($shopCustomerAddress, $this->request->getData());
            if ($this->ShopCustomerAddresses->save($shopCustomerAddress)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop address')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop address')));
            }
        }
        $shopCustomers = $this->ShopCustomerAddresses->ShopCustomers->find('list', ['limit' => 200]);
        $this->set(compact('shopCustomerAddress', 'shopCustomers'));
        $this->set('_serialize', ['shopCustomerAddress']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Address id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopCustomerAddress = $this->ShopCustomerAddresses->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCustomerAddress = $this->ShopCustomerAddresses->patchEntity($shopCustomerAddress, $this->request->getData());
            if ($this->ShopCustomerAddresses->save($shopCustomerAddress)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop address')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop address')));
            }
        }
        $shopCustomers = $this->ShopCustomerAddresses->ShopCustomers->find('list', ['limit' => 200]);
        $this->set(compact('shopCustomerAddress', 'shopCustomers'));
        $this->set('_serialize', ['shopCustomerAddress']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Address id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopCustomerAddress = $this->ShopCustomerAddresses->get($id);
        if ($this->ShopCustomerAddresses->delete($shopCustomerAddress)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop address')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop address')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
