<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/16/15
 * Time: 8:06 PM
 */

namespace Shop\View\Cell;

use Cake\Core\Configure;
use Content\View\Cell\ModuleCell;
use Cake\ORM\TableRegistry;

class RandomCategoryProductModuleCell extends ModuleCell
{
    public static $defaultParams = [
        'shop_category_id' => null,
        'element' => null,
    ];

    public static function inputs()
    {
        return [
          'shop_category_id' => [
              'type' => 'select',
              'options' => TableRegistry::get('Shop.ShopCategories')->find('treeList')
          ],
            'element' => []
        ];
    }

    public function display()
    {
        $this->loadModel('Shop.ShopProducts');

        $catId = $this->params['shop_category_id'];
        if (!$catId) {
            $catId = Configure::read('Shop.Catalogue.index_category_id');
        }
        $element = ($this->params['element']) ?: 'Shop.RandomCategoryProduct/default';

        $products = $this->ShopProducts
            ->find('list')
            ->where(['shop_category_id' => $catId, 'is_published' => true])
            ->toArray();

        $product = null;
        if (count($products) > 0) {
            $productId = array_rand($products);

            $product = $this->ShopProducts->get($productId, [
                'contain' => ['ShopCategories'],
                'media' => true,
            ]);
        }

        $this->set(compact('element', 'product'));
    }
}