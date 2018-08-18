<?php
namespace Shop\View\Cell;

use Cake\View\Cell;

/**
 * CategoriesCell cell
 */
class CategoryCell extends Cell
{

    public $modelClass = "Shop.ShopCategories";

    public $shopCategory;

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['shopCategory'];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
    }

    public function products($categoryId = null, $options = [])
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

        $this->loadModel('Shop.ShopProducts');
        $products = $this->ShopProducts
            ->find('all', ['media' => true])
            ->find('published')
            //->find('media')
            ->where(['shop_category_id' => $categoryId, 'parent_id IS NULL']);

        //$this->set('category', $category);
        $this->set('shopProducts', $products->all());
        $this->set('categoryId', $categoryId);
        $this->set('options', $options);
    }
}
