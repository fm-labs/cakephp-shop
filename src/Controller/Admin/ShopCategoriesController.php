<?php
namespace Shop\Controller\Admin;

use Content\Lib\ContentManager;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Media\Lib\Media\MediaManager;
use Shop\Controller\Admin\AppController;

/**
 * ShopCategories Controller
 *
 * @property \Shop\Model\Table\ShopCategoriesTable $ShopCategories
 */
class ShopCategoriesController extends AppController
{

    public $modelClass = "Shop.ShopCategories";

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->ShopCategories->locale($this->locale);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function table()
    {
        $this->paginate = [
            'contain' => ['ParentShopCategories'],
            'order' => ['ShopCategories.lft ASC'],
            'limit' => 100,
            'media' => true
        ];
        $shopCategories = $this->paginate($this->ShopCategories)->toArray();

        $shopCategoriesTree = $this->ShopCategories->find('treeList')->toArray();

        $this->set('shopCategories', $shopCategories);
        $this->set('shopCategoriesTree', $shopCategoriesTree);
        $this->set('_serialize', ['shopCategories']);
    }

    public function quick()
    {
        if ($this->request->is(['post','put'])) {
            $id = $this->request->data('shop_category_id');
            if ($id) {
                $this->redirect(['action' => 'edit', $id]);
                return;
            }
        }

        $this->Flash->error('Bad Request');
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function index()
    {
    }

    public function treeData()
    {
        $this->viewBuilder()->className('Json');

        $id = $this->request->query('id');
        $conditions = ($id == '#') ? ['parent_id IS NULL'] : ['parent_id' => $id];
        $nodes = $this->ShopCategories->find()->where($conditions)->orderAsc('lft')->all()->toArray();

        //debug($pages);
        $treeData = [];
        array_walk($nodes, function ($val) use (&$treeData, &$id) {

            $publishedClass = ($val->is_published) ? 'published' : 'unpublished';
            $treeData[] = [
                'id' => $val->id,
                'text' => $val->name . " (". $val->id . ")",
                'children' => true,
                'icon' => 'shop_category ' . $publishedClass,
                'parent' => ($val->parent_id) ?: '#',
                'data' => [
                    'type' => $val->getPageType(),
                    'viewUrl' => Router::url($val->getPageAdminUrl()),
                ]
            ];
        });

        $this->set('treeData', $treeData);
        $this->set('_serialize', 'treeData');
    }

    public function treeView()
    {
        $id = $this->request->query('id');
        $this->setAction('manage', $id);
    }


    /**
     * View method
     *
     * @param string|null $id Shop Category id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ParentShopCategories', 'ChildShopCategories'],
            'media' => true
        ]);

        $this->set('shopCategory', $shopCategory);
        $this->set('_serialize', ['shopCategory']);
    }


    public function manage($id = null)
    {
        if (!$this->request->is('ajax')) {
            $this->redirect(['action' => 'index', 'id' => $id]);
        }

        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ParentShopCategories', 'ChildShopCategories', 'ShopProducts'],
            'media' => true
        ]);

        $this->set('shopCategory', $shopCategory);
        $this->set('_serialize', ['shopCategory']);
    }

    public function relatedCustomTexts($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->data);
            if ($this->ShopCategories->save($shopCategory)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop category')));
                return $this->redirect(['action' => 'manage', $id]);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop category')));
            }
        }

        $this->set('shopCategory', $shopCategory);
        $this->set('_serialize', ['shopCategory']);
    }

    public function relatedProducts($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ShopProducts'],
        ]);

        $this->set('shopCategory', $shopCategory);
        $this->set('_serialize', ['shopCategory']);
    }

    public function relatedPageMeta($id = null)
    {
        $PageMetas = TableRegistry::get('Content.PageMetas');

        $content = $this->ShopCategories->get($id, [
            'contain' => []
        ]);

        $pageMeta = $content->meta;
        if (!$pageMeta) {
            $pageMeta = $PageMetas->newEntity(
                ['model' => 'Shop.ShopCategories', 'foreignKey' => $content->id],
                ['validate' => false]
            );
        }

        if ($this->request->is(['put', 'post'])) {
            $pageMeta = $PageMetas->patchEntity($pageMeta, $this->request->data);
            if ($PageMetas->save($pageMeta)) {
                $this->Flash->success('Successful');
                $this->redirect(['action' => 'manage', $id]);
            } else {
                $this->Flash->error('Failed');
            }
        }

        $this->set('content', $content);
        $this->set('pageMeta', $pageMeta);
        $this->set('_serialize', ['content', 'pageMeta']);
    }

    public function relatedContentModules($id = null)
    {

        $content = $this->ShopCategories->get($id, [
            'contain' => ['ContentModules' => ['Modules']]
        ]);


        //@TODO Read custom sections from page layout
        $sections = ['main', 'top', 'bottom', 'before', 'after', 'left', 'right'];
        $sections = array_combine($sections, $sections);

        //$sectionsModules = $this->Pages->ContentModules->find()->where(['refscope' => 'Content.Pages', 'refid' => $id]);
        //debug($sectionsModules);

        $availableModules = $this->ShopCategories->ContentModules->Modules->find('list');

        $this->set('content', $content);
        $this->set('sections', $sections);
        $this->set('availableModules', $availableModules);

        $this->set('_serialize', ['content', 'sections', 'availableModules']);
    }



    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopCategory = $this->ShopCategories->newEntity();
        if ($this->request->is('post')) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->data);
            if ($this->ShopCategories->save($shopCategory)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop category')));
                return $this->redirect(['action' => 'edit', $shopCategory->id]);
            } else {
                debug($shopCategory->errors());
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop category')));
            }
        } else {
            $this->ShopCategories->patchEntity($shopCategory, $this->request->query, ['validate' => false]);
        }
        $parentShopCategories = $this->ShopCategories->find('treeList');
        $this->set(compact('shopCategory', 'parentShopCategories'));
        $this->set('_serialize', ['shopCategory']);
    }

    public function linkModule($id = null)
    {
        $this->loadModel("Shop.ShopCategories");
        $contentModule = $this->ShopCategories->ContentModules->newEntity(
            ['refscope' => 'Shop.ShopCategories', 'refid' => $id],
            ['validate' => false]
        );
        if ($this->request->is(['post', 'put'])) {
            $contentModule = $this->ShopCategories->ContentModules->patchEntity($contentModule, $this->request->data);
            if ($this->ShopCategories->ContentModules->save($contentModule)) {
                $this->Flash->success(__d('shop', 'The content module has been saved for Shop Category {0}.', $id));
            } else {
                $this->Flash->error(__d('shop', 'The content module could not be saved for Shop Category {0}.', $id));
            }
            return $this->redirect(['action' => 'edit', $id]);
        }
    }
    /**
     * Edit method
     *
     * @param string|null $id Shop Category id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ParentShopCategories', 'ShopTags', 'ShopProducts', 'ContentModules' => ['Modules']],
            'media' => true
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->data);
            if ($this->ShopCategories->save($shopCategory)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop category')));
                return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop category')));
            }
        }
        $parentShopCategories = $this->ShopCategories->find('treeList');
        $galleryList = $this->_getGalleryList();

        $descShort = $this->_getShopText($id, 'desc_short_text');
        $descLong = $this->_getShopText($id, 'desc_long_text');

        $tags = $this->ShopCategories->ShopTags->find('list');


        //$availableModules = $this->ShopCategories->ContentModules->Modules->find('list');
        //$this->set('availableModules', $availableModules);
        //$this->set('contentSections', ContentManager::getContentSections());

        $this->set(compact('shopCategory', 'parentShopCategories', 'galleryList', 'descShort', 'descLong', 'tags'));
        $this->set('_serialize', ['shopCategory']);
    }

    protected function _getShopText($id, $scope)
    {
        return $this->ShopCategories->ShopTexts->find()->where([
            'model' => 'Shop.Categories',
            'model_id' => $id,
            'model_scope' => $scope,
            'locale' => $this->locale,
        ])->first();
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Category id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //$this->request->allowMethod(['post', 'delete']);
        $shopCategory = $this->ShopCategories->get($id);
        if ($this->ShopCategories->delete($shopCategory)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop category')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop category')));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function moveUp($id = null) {
        $shopCategory = $this->ShopCategories->get($id, ['contain' => []]);

        if ($this->ShopCategories->moveUp($shopCategory)) {
            $this->Flash->success(__d('shop', 'The {0} has been moved up.', __d('shop', 'shop category')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be moved. Please, try again.', __d('shop', 'shop category')));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function moveDown($id = null) {
        $shopCategory = $this->ShopCategories->get($id, ['contain' => []]);

        if ($this->ShopCategories->moveDown($shopCategory)) {
            $this->Flash->success(__d('shop', 'The {0} has been moved down.', __d('shop', 'shop category')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be moved. Please, try again.', __d('shop', 'shop category')));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function preview($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, ['contain' => []]);
        $this->redirect($shopCategory->url);
    }

    public function repair()
    {
        $this->ShopCategories->recover();
        $this->Flash->success(__d('shop', 'Shop Category tree recovery has been executed'));
        $this->redirect($this->referer(['action' => 'index']));
    }



    public function setImage($id = null)
    {
        $scope = $this->request->query('scope');
        $multiple = $this->request->query('multiple');

        $this->ShopCategories->behaviors()->unload('Media');
        $content = $this->ShopCategories->get($id, [
            'contain' => [],
            'media' => true
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->ShopCategories->patchEntity($content, $this->request->data);
            //$content->$scope = $this->request->data[$scope];
            if ($this->ShopCategories->save($content)) {
                $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','content')));

                if (!$this->request->is('iframe')) {
                    return $this->redirect(['action' => 'edit', $content->id]);
                }
            } else {
                $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','content')));
            }
        } else {
        }

        $mm = MediaManager::get('shop');
        $files = $mm->getSelectListRecursiveGrouped();
        $this->set('imageFiles', $files);
        $this->set('scope', $scope);
        $this->set('multiple', $multiple);

        $this->set(compact('content'));
        $this->set('_serialize', ['content']);
    }

    public function deleteImage($id = null)
    {
        $scope = $this->request->query('scope');

        $this->ShopCategories->behaviors()->unload('Media');
        $content = $this->ShopCategories->get($id, [
            'contain' => [],
            'media' => true
        ]);

        if (!in_array($scope, ['preview_image_file', 'featured_image_file'])) {
            throw new BadRequestException('Invalid scope');
        }

        $content->accessible($scope, true);
        $content->set($scope, '');

        if ($this->ShopCategories->save($content)) {
            $this->Flash->success(__d('shop','The {0} has been saved.', __d('shop','content')));
        } else {
            $this->Flash->error(__d('shop','The {0} could not be saved. Please, try again.', __d('shop','content')));
        }
        return $this->redirect(['action' => 'edit', $content->id]);
    }

}
