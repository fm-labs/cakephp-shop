<?php
namespace Shop\Controller;

use Content\Controller\Component\FrontendComponent;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Shop\Model\Table\ShopCategoriesTable;

/**
 * Categories Controller
 *
 * @property ShopCategoriesTable $ShopCategories
 * @property FrontendComponent $Frontend
 */
class CategoriesController extends AppController
{
    public $modelClass = 'Shop.ShopCategories';

    public $viewClass = 'Shop.ShopCategory';

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Shop.Cart');
        $this->Frontend->setRefScope('Shop.ShopCategories');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($id = null)
    {

        if ($id === null && Configure::check('Shop.Catalogue.index_category_id')) {
            $id = Configure::read('Shop.Catalogue.index_category_id');
            $this->redirect(['action' => 'view', $id]);
            return;
        }

        $this->paginate = [
            'contain' => ['ShopProducts' => []],
            'limit' => 100,
            //'conditions' => ['ShopCategories.parent_id IS' => $id],
            'published' => true,
            'media' => true
        ];

        $categories = $this->paginate($this->ShopCategories);
        $this->set('shopCategories', $categories->toArray());
        $this->set('_serialize', ['shopCategories']);
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
        $slug = null;
        $id = ($id) ?: $this->request->param('category_id');
        $id = ($id) ?: $this->request->query('category_id');

        // check if id is numeric or a string
        //@TODO Sanitize user input
        if ($id && !is_numeric($id)) {
            $slug = (string) $id;
            $id = null;
        }

        // If no category ID found,
        // attempt to resolve category from slug
        if (!$id) {
            $slug = ($slug) ?: $this->request->param('category');
            $slug = ($slug) ?: $this->request->query('category');
            if ($slug) {
                //@TODO Replace with slug finder
                $_category = $this->ShopCategories->find('published')->where(['slug' => $slug])->first();
                if ($_category) {
                    $id = $_category->id;
                }
            }
        }

        // Category Identifier missing
        // @TODO Log not found error
        if (!$id) {
           $this->Flash->error(__d('shop', 'Sorry, nothing there'));
            $this->redirect($this->referer('/shop'));
        }

        $this->Frontend->setRefId($id);

        $shopCategory = $this->ShopCategories->get($id, [
            'contain' => ['ParentShopCategories', 'ChildShopCategories', 'ShopProducts', 'ShopTags'],
            //'published' => true,
            'media' => true
        ]);


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


        // Template injection
        //@TODO Sanitize input vars
        $template = $this->request->query('template');
        $template = ($template) ?: $shopCategory->view_template;
        $template = ($template) ? strtolower($template) : null;
        $template = ($template == 'index') ? 'subcategories_grid' : $template; // legacy support
        $template = ($template == 'index2') ? 'subcategories' : $template; // legacy support
        $template = ($template == 'default') ? null : $template;
        //$template = ($template) ?: 'view_products_grid';

        if ($template && !preg_match('/^view\_/', $template)) {
            $template = 'view_' . $template;
        }

        $this->set('shopCategory', $shopCategory);
        $this->set('template', $template);
        $this->set('_serialize', ['shopCategory']);
        $this->render($template);
    }

    public function browse($id = null)
    {
        $this->setAction('index', $id);
    }
}
