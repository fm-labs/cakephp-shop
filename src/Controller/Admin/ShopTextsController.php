<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

/**
 * ShopTexts Controller
 *
 * @property \Shop\Model\Table\ShopTextsTable $ShopTexts
 */
class ShopTextsController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            //'contain' => ['Models']
        ];
        $this->set('shopTexts', $this->paginate($this->ShopTexts));
        $this->set('_serialize', ['shopTexts']);
    }

    /**
     * View method
     *
     * @param string|null $id Shop Text id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopText = $this->ShopTexts->get($id, [
            //'contain' => ['Models']
        ]);
        $this->set('shopText', $shopText);
        $this->set('_serialize', ['shopText']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopText = $this->ShopTexts->newEmptyEntity();
        if ($this->request->is('post')) {
            $shopText = $this->ShopTexts->patchEntity($shopText, $this->request->getData());
            if ($this->ShopTexts->save($shopText)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop text')));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop text')));
            }
        }
        //$models = $this->ShopTexts->Models->find('list', ['limit' => 200]);
        $this->set(compact('shopText', 'models'));
        $this->set('_serialize', ['shopText']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Text id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$id) {
            $shopText = $this->ShopTexts->newEntity($this->request->getQuery());
        } else {
            $shopText = $this->ShopTexts->get($id, [
                'contain' => [],
            ]);
        }
        $redirect = $this->request->getQuery('redirect');
        if (!$redirect) {
            $redirect = ['action' => 'index'];
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (!isset($data['text']) && isset($data[$data['model_scope']])) {
                $data['text'] = $data[$data['model_scope']];
                unset($data[$data['model_scope']]);
            }
            $shopText = $this->ShopTexts->patchEntity($shopText, $data);
            if ($this->ShopTexts->save($shopText)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop text')));

                return $this->redirect($redirect);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop text')));
            }
        }
        //$models = $this->ShopTexts->Models->find('list', ['limit' => 200]);
        $this->set(compact('shopText', 'redirect'));
        $this->set('_serialize', ['shopText']);
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function edit_iframe($id = null)
    {
        $this->layout = "Backend.iframe";
        if ($id !== null) {
            $shopText = $this->ShopTexts->get($id, [
                'contain' => [],
            ]);
        } else {
            $model = $this->request->getQuery('model');
            $modelId = $this->request->getQuery('model_id');
            $modelScope = $this->request->getQuery('model_scope');
            $locale = $this->request->getQuery('locale');
            $shopText = $this->ShopTexts->find()->where([
                'model' => $model,
                'model_id' => $modelId,
                'model_scope' => $modelScope,
                'locale' => (string)$locale,
            ])->first();

            if (!$shopText) {
                $shopText = $this->ShopTexts->newEntity($this->request->getQuery());
            }
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopText = $this->ShopTexts->patchEntity($shopText, $this->request->getData());
            if ($this->ShopTexts->save($shopText)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop text')));
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop text')));
            }
        }
        //$models = $this->ShopTexts->Models->find('list', ['limit' => 200]);
        $this->set(compact('shopText', 'models'));
        $this->set('_serialize', ['shopText']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Text id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopText = $this->ShopTexts->get($id);
        if ($this->ShopTexts->delete($shopText)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop text')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop text')));
        }

        return $this->redirect(['action' => 'index']);
    }
}
