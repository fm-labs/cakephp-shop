<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/20/15
 * Time: 4:12 PM
 */

namespace Shop\Controller;

use Cake\Core\Configure;

class CatalogueController extends AppController
{
    public function index()
    {
        $this->loadModel('Shop.ShopCategories');

        $scope = [];
        $indexCategoryId = Configure::read('Shop.Catalogue.index_category_id');
        if ($indexCategoryId) {
            $scope = ['id' => $indexCategoryId];
        }

        $category = $this->ShopCategories
            ->find()
            ->where($scope)
            ->first();

        $this->redirect($category->url);
    }
}