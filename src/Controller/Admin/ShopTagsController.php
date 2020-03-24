<?php
namespace Shop\Controller\Admin;

use Shop\Controller\Admin\AppController;

/**
 * ShopTags Controller
 *
 * @property \Shop\Model\Table\ShopTagsTable $ShopTags
 */
class ShopTagsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('shopTags', $this->paginate($this->ShopTags));
        $this->set('_serialize', ['shopTags']);
    }

    /**
     * View method
     *
     * @param string|null $id Shop Tag id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopTag = $this->ShopTags->get($id, [
            'contain' => ['ShopProductsTags'],
        ]);
        $this->set('shopTag', $shopTag);
        $this->set('_serialize', ['shopTag']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopTag = $this->ShopTags->newEntity();
        if ($this->request->is('post')) {
            $shopTag = $this->ShopTags->patchEntity($shopTag, $this->request->getData());
            if ($this->ShopTags->save($shopTag)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop tag')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop tag')));
            }
        }
        $this->set(compact('shopTag'));
        $this->set('_serialize', ['shopTag']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Tag id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopTag = $this->ShopTags->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopTag = $this->ShopTags->patchEntity($shopTag, $this->request->getData());
            if ($this->ShopTags->save($shopTag)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop tag')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop tag')));
            }
        }
        $this->set(compact('shopTag'));
        $this->set('_serialize', ['shopTag']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Tag id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopTag = $this->ShopTags->get($id);
        if ($this->ShopTags->delete($shopTag)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop tag')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop tag')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
