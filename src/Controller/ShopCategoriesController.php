<?php
namespace Shop\Controller;

use Content\Controller\Component\FrontendComponent;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Shop\Model\Table\ShopCategoriesTable;

/**
 * Categories Controller
 *
 * @property ShopCategoriesTable $ShopCategories
 * @property FrontendComponent $Frontend
 */
class ShopCategoriesController extends AppController
{
    public $modelClass = 'Shop.ShopCategories';

    public $viewClass = 'Shop.ShopCategory';

    public function initialize()
    {
        parent::initialize();

        $this->Frontend->setRefScope('Shop.ShopCategories');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($id = null)
    {
        $this->paginate = [
            'contain' => ['ParentShopCategories'],
            'limit' => 100,
            'conditions' => ['ShopCategories.parent_id' => $id],
            'published' => true,
            'media' => true
        ];

        try {
            $shopCategory = $this->ShopCategories->get($id);
        } catch (\Exception $ex) {
            $shopCategory = null;
        }

        if (!$shopCategory) {
            $this->Flash->error(__d('shop','Category not found'));
            $this->redirect(['controller' => 'Catalogue', 'action' => 'index']);
        }

        $categories = $this->paginate($this->ShopCategories);
        $this->set('shopCategory', $shopCategory);
        $this->set('categories', $categories);
        $this->set('_serialize', ['categories']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function productslisting($categoryId = null)
    {
        $this->loadModel('Shop.ShopProducts');
        $this->loadModel('Shop.ShopCategories');

        if ($categoryId === null) {
            $categoryId = $this->request->query('category_id');
        }

        $this->paginate = [
            //'contain' => ['ParentShopCategories'],
            'limit' => 9,
            'conditions' => ['ShopProducts.shop_category_id' => $categoryId],
            'published' => true,
            'media' => true
        ];

        $view = null;
        $category = null;
        if ($categoryId) {
            $shopCategory = $this->ShopCategories->get($categoryId);
            //$this->set('title', $shopCategory->name);

            $view = ($shopCategory->view_template) ?: $view;
        }

        $shopProducts = $this->paginate($this->ShopProducts);
        $this->set('shopCategory', $category);
        $this->set('shopProducts', $shopProducts);
        $this->set('_serialize', ['shopCategory']);

        //$this->render($view);
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($id === null) {

            if (isset($this->request->params['category_id'])) {
                $id = (int) $this->request->params['category_id'];
            } elseif ($this->request->query('category_id')) {
                $id = (int) $this->request->query('category_id');
            } else {
                // By Slug
                $slug = null;
                if (isset($this->request->params['category'])) {
                    $slug = $this->request->params['category'];
                } elseif ($this->request->query('category')) {
                    $slug = $this->request->query('category');
                }
                if ($slug) {
                    $_category = $this->ShopCategories->find('published')->where(['slug' => $slug])->first();
                    if ($_category) {
                        $id = $_category->id;
                    }
                }
            }

        }

        if (!$id) {
           throw new BadRequestException('Shop category identifier is missing');
        }

        $this->Frontend->setRefId($id);

        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ParentShopCategories', 'ChildShopCategories', 'ShopProducts', 'ShopTags'],
            'published' => true,
            'media' => true
        ]);

        $view = ($shopCategory->view_template) ?: null;

        // Aliasing
        if ($shopCategory->is_alias) {
            $shopCategory = $this->ShopCategories->get($shopCategory->alias_id, [
                'contain' => ['ParentShopCategories', 'ChildShopCategories', 'ShopProducts', 'ShopTags'],
                'media' => true
            ]);

            // @TODO Inject alias shop category id into products
        }


        // force canonical url
        if (Configure::read('Shop.Router.forceCanonical')) {
            $here = Router::normalize($this->request->here);
            $canonical = Router::normalize($shopCategory->url);

            if ($here != $canonical) {
                $this->redirect($canonical, 301);
                return;
            }
        }

        $this->set('shopCategory', $shopCategory);
        $this->set('_serialize', ['shopCategory']);

        $this->render($view);
    }

    public function browse($id = null)
    {
        $this->setAction('index', $id);
    }
}
