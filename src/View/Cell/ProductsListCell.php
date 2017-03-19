<?php
namespace Shop\View\Cell;

use Cake\ORM\TableRegistry;
use Cake\View\Cell;

/**
 * ProductsList cell
 */
class ProductsListCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($scope = [])
    {
        $Table = TableRegistry::get('Shop.ShopProducts');
        $products =  $Table->find('published'); //->find('media');
        if ($scope) {
            $products->where($scope);
        }

        $this->set('products', $products->all());
    }

    public function category($categoryId = null, $options = [])
    {
        //@TODO check if object is instance of EntityInterface
        if (is_object($categoryId)) {
            $categoryId = $categoryId->id;
        }

        //die($categoryId);
        $options = array_merge([
            'add_to_cart' => null,
            'show_price' => null,
            'show_teaser' => null,
        ], $options);

        $Table = TableRegistry::get('Shop.ShopProducts');
        $products =  $Table->find('published'); //->find('media');
        $products->where(['shop_category_id' => $categoryId, 'parent_id IS NULL']);

        //$this->set('category', $category);
        $this->set('shopProducts', $products->all());
        $this->set('categoryId', $categoryId);
        $this->set('options', $options);
    }

    public function paginate($categoryId = null)
    {
        $this->set('categoryId', $categoryId);
    }
}
