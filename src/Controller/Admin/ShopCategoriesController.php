<?php
declare(strict_types=1);

namespace Shop\Controller\Admin;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Content\Lib\ContentManager;
use Media\Lib\Media\MediaManager;

/**
 * ShopCategories Controller
 *
 * @property \Shop\Model\Table\ShopCategoriesTable $ShopCategories
 */
class ShopCategoriesController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopCategories";

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Backend.TreeIndex',
        //'view'      => 'Backend.View',
        'add'       => 'Backend.Add',
        'edit'      => 'Backend.Edit',
        'delete'    => 'Backend.Delete',
        'publish'   => 'Backend.Publish',
        'unpublish' => 'Backend.Unpublish',
        'sort'      => 'Backend.TreeSort',
        /*
        'moveUp'    => 'Backend.TreeMoveUp',
        'moveDown'  => 'Backend.TreeMoveDown',
        */
    ];

    /**
     * @param \Cake\Event\Event $event
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->ShopCategories->setLocale($this->locale);

        $this->Action->registerInline('preview', ['label' => __d('shop', 'Preview'), 'attrs' => ['data-icon' => 'search', 'target' => '_blank']]);
    }

    /**
     * @deprecated Use search instead
     */
    public function quick()
    {
        if ($this->request->is(['post', 'put'])) {
            $id = $this->request->getData('shop_category_id');
            if ($id) {
                $this->redirect(['action' => 'edit', $id]);

                return;
            }
        }

        $this->Flash->error('Bad Request');
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * Index method
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['ParentShopCategories'],
            'order' => ['ShopCategories.lft ASC'],
            'limit' => 100,
            'media' => true,
        ];

        $this->set('tree.displayField', 'name');
        //$this->set('fields.whitelist', ['name', 'language', 'is_published']);
        $this->set('fields', [
            'name',
            'featured_image_file' => ['formatter' => 'media_file'],
            'language' => ['formatter' => function ($val, $row, $args, $view) {
                $links = [];
                foreach (Configure::read('Shop.locales') as $_locale => $_localeName) {
                    $links[] = $view->Html->link($_localeName, ['action' => 'edit', $row->id, 'locale' => $_locale], ['data-locale' => $_locale]);
                }

                return join("&nbsp;", $links);
            }],
            'is_published',
        ]);
        $this->set('fields.whitelist', ['name', 'featured_image_file', 'language', 'is_published']);

        $this->Action->execute();
    }

    /**
     * View method
     *
     * @param string|null $id Shop Category id.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ParentShopCategories', 'ChildShopCategories'],
            'media' => true,
        ]);

        $this->set('model', 'Shop.ShopCategories');
        $this->set('entity', $shopCategory);
        //$this->set('shopCategory', $shopCategory);
        $this->set('_serialize', ['entity']);

        $this->noActionTemplate = true;
        $this->Action->execute();
    }

    /**
     * @param null $id
     */
    public function preview($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, ['contain' => []]);
        $url = $shopCategory->getViewUrl();
        $url['prefix'] = false;
        $url['admin'] = false;
        $url['_tk'] = uniqid(time());

        $this->redirect($url);
    }

    /**
     * @param null $id
     */
    public function edit($id = null)
    {
        $shopCategory = $this->ShopCategories
            ->find('all', ['media' => true])
            //->find('media')
            //->find('attributes')
            ->where(['ShopCategories.id' => $id])
            ->contain(['ParentShopCategories', 'ShopTags', 'ShopProducts'/*, 'ContentModules' => ['Modules']*/])
            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->getData());
            if ($this->ShopCategories->save($shopCategory)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop category')));

                return;
                //return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop category')));
            }
        }
        $parentShopCategories = $this->ShopCategories->find('treeList');
        //$parentShopCategories = $this->ShopCategories->find('list');

        $tagList = $this->ShopCategories->ShopTags->find('list')->toArray();

        $this->set(compact('shopCategory', 'parentShopCategories', 'tagList'));

        $this->noActionTemplate = true;
        $this->Action->execute();
    }

    /**
     * Manage method
     *
     * @param string|null $id Shop Category id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function manage($id = null)
    {
        $shopCategory = $this->ShopCategories
            ->find()
            //->find('media')
            //->find('attributes')
            ->where(['ShopCategories.id' => $id])
            ->contain(['ParentShopCategories', 'ShopTags', 'ShopProducts', 'ContentModules' => ['Modules']])
            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->getData());
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


        //$teaserTemplates = ContentManager::getAvailableViewTemplates('ShopCategories');
        $viewTemplates = $teaserTemplates = ContentManager::getAvailableViewTemplates('Categories', function ($val) {
            /*
            if (preg_match('/^view_/', $val)) {
                return true;
            }
            return false;
            */
            return true;
        });
        $this->set(compact('teaserTemplates', 'viewTemplates'));

        $this->set(compact('shopCategory', 'parentShopCategories', 'galleryList', 'descShort', 'descLong', 'tags'));
        //$this->set('attributeSets', TableRegistry::getTableLocator()->get('Eav.EavAttributeSets')->find('list')->toArray());
        $this->set('_serialize', ['shopCategory']);
    }

    /**
     * @param $id
     * @param $scope
     * @return mixed
     */
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
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function relatedCustomTexts($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->getData());
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

    /**
     * @param null $id
     */
    public function relatedProducts($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => [],
        ]);

        $shopProducts = $this->ShopCategories->ShopProducts->find()
            ->where(['shop_category_id' => $id])
            ->all();

        $this->set('shopCategory', $shopCategory);
        $this->set('shopProducts', $shopProducts);
        $this->set('_serialize', ['shopCategory', 'shopProducts']);
    }

    /**
     * @param null $id
     */
    public function relatedPageMeta($id = null)
    {
        $PageMetas = TableRegistry::getTableLocator()->get('Content.PageMetas');

        $content = $this->ShopCategories->get($id, [
            'contain' => [],
        ]);

        $pageMeta = $content->meta;
        if (!$pageMeta) {
            $pageMeta = $PageMetas->newEntity(
                ['model' => 'Shop.ShopCategories', 'foreignKey' => $content->id],
                ['validate' => false]
            );
        }

        if ($this->request->is(['put', 'post'])) {
            $pageMeta = $PageMetas->patchEntity($pageMeta, $this->request->getData());
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

    /**
     * @param null $id
     */
    public function relatedContentModules($id = null)
    {
        $content = $this->ShopCategories->get($id, [
            'contain' => ['ContentModules' => ['Modules']],
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
        $shopCategory = $this->ShopCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $shopCategory = $this->ShopCategories->patchEntity($shopCategory, $this->request->getData());
            if ($this->ShopCategories->save($shopCategory)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop category')));

                return $this->redirect(['action' => 'edit', $shopCategory->id]);
            } else {
                debug($shopCategory->getErrors());
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop category')));
            }
        } else {
            $this->ShopCategories->patchEntity($shopCategory, $this->request->getQuery(), ['validate' => false]);
        }
        $parentShopCategories = $this->ShopCategories->find('treeList');
        $this->set(compact('shopCategory', 'parentShopCategories'));
        $this->set('_serialize', ['shopCategory']);
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     */
    public function linkModule($id = null)
    {
        $this->loadModel("Shop.ShopCategories");
        $contentModule = $this->ShopCategories->ContentModules->newEntity(
            ['refscope' => 'Shop.ShopCategories', 'refid' => $id],
            ['validate' => false]
        );
        if ($this->request->is(['post', 'put'])) {
            $contentModule = $this->ShopCategories->ContentModules->patchEntity($contentModule, $this->request->getData());
            if ($this->ShopCategories->ContentModules->save($contentModule)) {
                $this->Flash->success(__d('shop', 'The content module has been saved for Shop Category {0}.', $id));
            } else {
                $this->Flash->error(__d('shop', 'The content module could not be saved for Shop Category {0}.', $id));
            }

            return $this->redirect(['action' => 'edit', $id]);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Category id.
     * @return void Redirects to index.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
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

    /**
     * @param null $id
     */
    public function moveUp($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, ['contain' => []]);

        if ($this->ShopCategories->moveUp($shopCategory)) {
            $this->Flash->success(__d('shop', 'The {0} has been moved up.', __d('shop', 'shop category')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be moved. Please, try again.', __d('shop', 'shop category')));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * @param null $id
     */
    public function moveDown($id = null)
    {
        $shopCategory = $this->ShopCategories->get($id, ['contain' => []]);

        if ($this->ShopCategories->moveDown($shopCategory)) {
            $this->Flash->success(__d('shop', 'The {0} has been moved down.', __d('shop', 'shop category')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be moved. Please, try again.', __d('shop', 'shop category')));
        }
        $this->redirect($this->referer(['action' => 'index']));
    }

    public function repair()
    {
        $this->ShopCategories->recover();
        $this->Flash->success(__d('shop', 'Shop Category tree recovery has been executed'));
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     * @deprecated
     */
    public function setImage($id = null)
    {
        $scope = $this->request->getQuery('scope');
        $multiple = $this->request->getQuery('multiple');

        $this->ShopCategories->behaviors()->unload('Media');
        $content = $this->ShopCategories->get($id, [
            'contain' => [],
            'media' => true,
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->ShopCategories->patchEntity($content, $this->request->getData());
            //$content->$scope = $this->request->data[$scope];
            if ($this->ShopCategories->save($content)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'content')));

                if (!$this->request->is('iframe')) {
                    return $this->redirect(['action' => 'edit', $content->id]);
                }
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'content')));
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

    /**
     * @param null $id
     * @return \Cake\Http\Response|null
     * @throws \Shop\Controller\Admin\BadRequestException
     * @deprecated
     */
    public function deleteImage($id = null)
    {
        $scope = $this->request->getQuery('scope');

        $this->ShopCategories->behaviors()->unload('Media');
        $content = $this->ShopCategories->get($id, [
            'contain' => [],
            'media' => true,
        ]);

        if (!in_array($scope, ['preview_image_file', 'featured_image_file'])) {
            throw new BadRequestException('Invalid scope');
        }

        $content->setAccess($scope, true);
        $content->set($scope, '');

        if ($this->ShopCategories->save($content)) {
            $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'content')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'content')));
        }

        return $this->redirect(['action' => 'edit', $content->id]);
    }
}
