<?php
namespace Shop\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;
use Shop\Controller\Component\CartComponent;
use Shop\Controller\Component\ShopComponent;
use User\Controller\Component\AuthComponent;

/**
 * class ProductsController
 *
 * @property \Shop\Model\Table\ShopProductsTable $ShopProducts
 * @property ShopComponent $Shop
 * @property AuthComponent $Auth
 * @property CartComponent $Cart
 */
class ProductsController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = "Shop.ShopProducts";

    /**
     * Initialize
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Shop.Cart');

        $this->Frontend->setRefScope('Shop.ShopProducts');

        $this->Auth->allow(null);
    }

    /**
     * @param null $categoryId
     */
    public function index($categoryId = null)
    {
        if ($categoryId === null) {
            $categoryId = $this->request->getQuery('category_id');
        }

        $this->Frontend->setRefScope('Shop.ShopCategories');
        $this->Frontend->setRefId($categoryId);

        $this->paginate = [
            //'contain' => ['ShopCategories'],
            'conditions' => ['ShopProducts.is_published' => true],
            'media' => true,
            'for_customer' => $this->Shop->getCustomerId()
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
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->setClassName('Shop.ShopProduct');

        if ($this->request->is(['post', 'put']) && $this->request->getData('id')) {
            $id = $this->request->getData('id');
            $shopProduct = $this->ShopProducts->get($id, ['contain' => []]);

            return $this->redirect($shopProduct->url);
        }

        if ($id === null) {
            if ($this->request->getQuery('id')) {
                $id = $this->request->getQuery('id');
            } elseif (isset($this->request->query['product_id'])) {
                $id = $this->request->query['product_id'];
            } elseif ($this->request->getParam('product_id')) {
                $id = $this->request->getParam('product_id');
            }
        }

        $shopProductVersionId = $id;

        $this->ShopProducts->setLocale($this->Locale->getLocale());
        $shopProduct = $this->ShopProducts->get($id, [
            'contain' => ['ParentShopProducts'],
            'media' => true,
            'for_customer' => $this->Shop->getCustomerId()
        ]);

        /*
        if ($shopProduct->parent_id) {
            $this->redirect($shopProduct->parent_shop_product->url);

            return;
            //$shopProductVersionId = $shopProduct->id;
            //$shopProduct = $shopProduct->parent_shop_product;
            //$this->request->data['refid'] = $shopProductVersionId;
        }
        */

        $shopCategory = $this->ShopProducts->ShopCategories->get($shopProduct->shop_category_id, ['media' => true, 'contain' => []]);
        $shopProduct->shop_category = $shopCategory;

        // force canonical url
        /*
        if (Configure::read('Shop.Router.forceCanonical')) {
            $here = Router::normalize($this->request->here);
            $canonical = Router::normalize($shopProduct->url);

            if ($here != $canonical) {
                $this->redirect($canonical, 301);

                return;
            }
        }
        */

        if (!$shopProduct) {
            throw new NotFoundException();
        }

        if (!$shopProduct->is_published) {
            throw new NotFoundException();
        }

        $this->set('shopProduct', $shopProduct);
        $this->set('shopProductVersionId', $shopProductVersionId);
        $this->set('_serialize', ['shopProduct']);
    }
}
