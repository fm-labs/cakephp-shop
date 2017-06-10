<?php
namespace Shop\View\Cell;

use Cake\View\Cell;
use Shop\Model\Table\ShopProductsTable;

/**
 * CategoriesCell cell
 *
 * @property ShopProductsTable $ShopProducts
 */
class ProductCell extends Cell
{

    public $modelClass = "Shop.ShopProducts";

    public $shopProduct;

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = ['shopProduct'];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
    }
}
