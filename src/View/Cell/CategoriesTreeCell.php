<?php
declare(strict_types=1);

namespace Shop\View\Cell;

use Cake\View\Cell;

/**
 * CategoriesCell cell
 */
class CategoriesTreeCell extends Cell
{
    public $modelClass = "Shop.ShopCategories";

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
    public function display()
    {
    }

    public function adminMenu()
    {
        $this->loadModel('Shop.ShopCategories');
        $tree = $this->ShopCategories->find('threaded')->all();
        $this->set('shopCategories', $tree);
    }
}
