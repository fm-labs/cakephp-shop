<?php
namespace Shop\Controller\Admin;

use Backend\Controller\Component\ToggleComponent;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Media\Lib\Media\MediaManager;

/**
 * ShopProducts Controller
 *
 * @property \Shop\Model\Table\ShopProductsTable $ShopProducts
 * @property ToggleComponent $Toggle
 */
class ShopProductsController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopProducts";

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Backend.Index',
        'view'      => 'Backend.View',
        'add'       => 'Backend.Add',
        'edit'      => 'Backend.Edit',
        'media'      => 'Backend.Media',
        'publish'   => 'Backend.Publish',
        'unpublish'   => 'Backend.Unpublish'
    ];

    /**
     * Initialize method
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Backend.Toggle');
        $this->loadComponent('Search.Prg', [
            'actions' => ['index', 'search']
        ]);
    }

    /**
     * @param Event $event
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->ShopProducts->locale($this->locale);
    }

    /**
     * Index method
     */
    public function index()
    {
        $this->paginate = [
            //'limit' => 200,
            //'maxLimit' => 200,
            //'fields' => ['ShopProducts.id', 'ShopProducts.shop_category_id', 'ShopProducts.sku', 'ShopProducts.preview_image_file', 'ShopProducts.title', 'ShopProducts.price', 'ShopProducts.is_buyable', 'ShopProducts.is_published', 'ShopCategories.name'],
            'fields' => ['ShopProducts.id', 'ShopProducts.shop_category_id', 'ShopProducts.sku', 'ShopProducts.preview_image_file', 'ShopProducts.title', 'ShopProducts.price', 'ShopProducts.is_buyable', 'ShopProducts.is_published'],
            'order' => ['ShopProducts.title' => 'ASC', 'ShopProducts.shop_category_id' => 'ASC'],
            //'contain' => ['ShopCategories'],
            'media' => true
        ];

        $fields = [
            'title'  => ['formatter' => function ($val, $row, $args, $view) {
                return $view->Html->link(
                    $val,
                    ['action' => 'edit', $row->id]
                );
            }],
            'preview_image_file' => [
                'title' => 'Image',
                'type' => 'object',
                'formatter' => 'media_file'
            ],
            'sku',
            'price' => [
                'formatter' => 'currency'
            ],
            'is_buyable' => [
                'formatter' => null
            ],
            'is_published' => [
                'formatter' => null,
            ],
        ];
        $this->set('paginate', true);
        $this->set('ajax', true);
        $this->set('filter', false);
        $this->set('fields', $fields);
        $this->set('fields.whitelist', ['title', 'sku', 'preview_image_file', 'is_buyable', 'is_published']);

        $this->Action->execute();
    }

    /**
     * Search method
     */
    public function search()
    {
        $query = $this->ShopProducts->find('search', ['search' => $this->request->query]);
        $this->set('shopProducts', $this->paginate($query));
        $this->set('_serialize', ['shopProducts']);
        $this->render('index');
    }

    /**
     * @param null $id
     * @param $field
     * @todo Refactore with ToggleAction
     */
    public function toggle($id = null, $field)
    {
        $this->Toggle->toggleBoolean($this->ShopProducts, $id, $field);
    }

    /**
     * @deprecated Use Search isntead
     */
    public function quick()
    {
        if ($this->request->is(['post', 'put'])) {
            $id = $this->request->data('shop_product_id');
            if ($id) {
                $this->redirect(['action' => 'edit', $id]);

                return;
            }
        }

        $this->Flash->error('Bad Request');
        $this->redirect($this->referer(['action' => 'index']));
    }

    /**
     * View method
     *
     * @param string|null $id Shop Product id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shopProduct = $this->ShopProducts->get($id);

        $tabs = [];
        if ($shopProduct->type == "parent") {
            $tabs['child-products'] = [
                'title' => __d('shop', 'Productversions'),
                'url' => ['action' => 'index', 'qry' => ['parent_id' => $shopProduct->id]]
            ];
        }
        $this->set('tabs', $tabs);

        $this->set('entity', $shopProduct);

        $this->Action->execute();
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shopProduct = $this->ShopProducts->newEntity($this->request->query, ['validate' => false]);
        if ($this->request->is('post')) {
            //$shopProduct = $this->ShopProducts->patchEntity($shopProduct, $this->request->data);
            if ($this->ShopProducts->add($shopProduct, $this->request->data)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop product')));

                return $this->redirect(['action' => 'edit', $shopProduct->id]);
            } else {
                debug($shopProduct->errors());
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop product')));
            }
        }
        $this->set(compact('shopProduct'));
        $this->set('shopCategories', $this->_getCategoriesList());
        $this->set('_serialize', ['shopProduct']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shop Product id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shopProduct = $this->ShopProducts->get($id, [
            'contain' => ['ShopCategories'],
            'media' => true
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            //$shopProduct = $this->ShopProducts->patchEntity($shopProduct, $this->request->data);
            if ($this->ShopProducts->edit($shopProduct, $this->request->data)) {
                $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'shop product')));

                return $this->redirect(['action' => 'edit', $shopProduct->id]);
            } else {
                $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'shop product')));
            }
        }
        $this->set('parentShopProducts', $this->ShopProducts->find('list')->orderAsc('title'));
        $this->set('shopCategories', $this->_getCategoriesList());
        $this->set('galleryList', $this->_getGalleryList());
        $this->set('locales', Configure::read('Shop.locales'));
        $this->set('shopProduct', $shopProduct);
        $this->set('entity', $shopProduct);

        $tabs = [];
        if ($shopProduct->type == "parent") {
            $tabs['child-products'] = [
                'title' => __d('shop', 'Productversions'),
                'url' => ['action' => 'index', 'qry' => ['parent_id' => $shopProduct->id]]
            ];
        }
        $tabs['media'] = [
            'title' => __d('shop', 'Media'),
            'url' => ['action' => 'media', $shopProduct->id]
        ];
        $this->set('tabs', $tabs);

        $this->Action->execute();
    }

    /**
     * @param null $id
     */
    public function relatedProducts($id = null)
    {
        $shopProduct = $this->ShopProducts->get($id, [
            'contain' => ['ChildShopProducts'],
            'media' => true
        ]);
        $this->set(compact('shopProduct'));
        $this->set('_serialize', ['shopProduct']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shop Product id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shopProduct = $this->ShopProducts->get($id);
        if ($this->ShopProducts->delete($shopProduct)) {
            $this->Flash->success(__d('shop', 'The {0} has been deleted.', __d('shop', 'shop product')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be deleted. Please, try again.', __d('shop', 'shop product')));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @return \Cake\ORM\Query
     */
    protected function _getCategoriesList()
    {
        return $this->ShopProducts->ShopCategories->find('treeList');
    }

    /**
     * @param null $id
     * @return \Cake\Network\Response|null
     * @deprecated Use MediaBehavior instead
     */
    public function setImage($id = null)
    {
        $scope = $this->request->query('scope');
        $multiple = $this->request->query('multiple');

        $this->ShopProducts->behaviors()->unload('Media');
        $content = $this->ShopProducts->get($id, [
            'contain' => [],
            'media' => true
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $content = $this->ShopProducts->patchEntity($content, $this->request->data);
            //$content->$scope = $this->request->data[$scope];
            if ($this->ShopProducts->save($content)) {
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
     * @return \Cake\Network\Response|null
     * @deprecated Use Mediabehavior instead
     */
    public function deleteImage($id = null)
    {
        $scope = $this->request->query('scope');

        $this->ShopProducts->behaviors()->unload('Media');
        $content = $this->ShopProducts->get($id, [
            'contain' => []
        ]);

        if (!in_array($scope, ['preview_image_file', 'featured_image_file'])) {
            throw new BadRequestException('Invalid scope');
        }

        $content->accessible($scope, true);
        $content->set($scope, '');

        if ($this->ShopProducts->save($content)) {
            $this->Flash->success(__d('shop', 'The {0} has been saved.', __d('shop', 'content')));
        } else {
            $this->Flash->error(__d('shop', 'The {0} could not be saved. Please, try again.', __d('shop', 'content')));
        }

        return $this->redirect(['action' => 'edit', $content->id]);
    }
}
