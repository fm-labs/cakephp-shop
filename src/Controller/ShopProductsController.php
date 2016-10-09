<?php
namespace Shop\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use Shop\Controller\AppController;

/**
 * ShopProducts Controller
 *
 * @property \Shop\Model\Table\ShopProductsTable $ShopProducts
 */
class ShopProductsController extends AppController
{
    public $modelClass = "Shop.ShopProducts";

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Content.Locale');

        $this->Frontend->setRefScope('Shop.ShopProducts');
    }

    public function index($categoryId = null)
    {
        if ($categoryId === null) {
            $categoryId = $this->request->query('category_id');
        }

        $this->Frontend->setRefScope('Shop.ShopCategories');
        $this->Frontend->setRefId($categoryId);

        $this->paginate = [
            //'contain' => ['ShopCategories'],
            'conditions' => ['ShopProducts.is_published' => true],
            'media' => true,
        ];

        if ($categoryId) {
            $this->paginate['conditions']['ShopProducts.shop_category_id'] = $categoryId;
        }

        //debug($categoryId);
        //debug($this->paginate);

        $this->set('shopProducts', $this->paginate($this->ShopProducts));
        $this->set('_serialize', ['shopProducts']);
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
        if ($id === null) {

            if ($this->request->query('id')) {
                $id = $this->request->query('id');
            } elseif (isset($this->request->query['product_id'])) {
                $id = $this->request->query['product_id'];
            } elseif (isset($this->request->params['product_id'])) {
                $id = $this->request->params['product_id'];
            }
        }


        $this->ShopProducts->locale($this->Locale->getLocale());
        $shopProduct = $this->ShopProducts->get($id, [
            'contain' => ['ShopCategories'],
            'media' => true,
        ]);

        // force canonical url
        if (Configure::read('Shop.Router.forceCanonical')) {
            $here = Router::normalize($this->request->here);
            $canonical = Router::normalize($shopProduct->url);

            if ($here != $canonical) {
                $this->redirect($canonical, 301);
                return;
            }
        }

        if (!$shopProduct) {
            throw new NotFoundException();
        }

        if (!$shopProduct->is_published) {
            throw new NotFoundException();
        }

        $this->set('shopProduct', $shopProduct);
        $this->set('childProducts', $this->ShopProducts->findChildProducts($shopProduct->id)->toArray());
        $this->set('_serialize', ['shopProduct']);
    }
}
