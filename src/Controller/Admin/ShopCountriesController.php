<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopCountries Controller
 *
 * @property \Shop\Model\Table\ShopCountriesTable $ShopCountries
 */
class ShopCountriesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('shopCountries', $this->paginate($this->ShopCountries));
        $this->set('_serialize', ['shopCountries']);
    }

    /**
     * View method
     *
     * @param string|null $id Shop Country id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopCountry = $this->ShopCountries->get($id, [
            'contain' => []
        ]);
        $this->set('shopCountry', $shopCountry);
        $this->set('_serialize', ['shopCountry']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopCountry = $this->ShopCountries->newEntity();
        if ($this->request->is('post')) {
            $shopCountry = $this->ShopCountries->patchEntity($shopCountry, $this->request->data);
            if ($this->ShopCountries->save($shopCountry)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop country')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop country')));
            }
        }
        $this->set(compact('shopCountry'));
        $this->set('_serialize', ['shopCountry']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Country id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopCountry = $this->ShopCountries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCountry = $this->ShopCountries->patchEntity($shopCountry, $this->request->data);
            if ($this->ShopCountries->save($shopCountry)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop country')));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop country')));
            }
        }
        $this->set(compact('shopCountry'));
        $this->set('_serialize', ['shopCountry']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Country id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopCountry = $this->ShopCountries->get($id);
        if ($this->ShopCountries->delete($shopCountry)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop country')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop country')));
        }
        return $this->redirect(['action' => 'index']);
    }
}
