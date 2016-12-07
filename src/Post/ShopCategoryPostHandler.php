<?php

namespace Shop\Post;

use Content\Model\Entity\Page;
use Content\Page\AbstractPageType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Content\Post\BasePostHandler;
use Content\Post\DefaultPostHandler;
use Content\Post\PostHandlerInterface;
use Shop\Model\Entity\ShopCategory;

class ShopCategoryPostHandler implements PostHandlerInterface
{
    /**
     * @var ShopCategory
     */
    protected $category;

    public static function describe()
    {
        return [
            'title' => 'Shop Category',
            'modelClass' => 'Shop.ShopCategories'
        ];
    }

    public function __construct(EntityInterface $entity)
    {
        $this->category = $entity;
    }

    public function getViewUrl()
    {
        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'ShopCategories',
            'action' => 'view',
            'category_id' => $this->category->id,
            //'category' => $this->category->slug,
            'category' => $this->category->url_path,
        ];
    }

    public function getAdminUrl()
    {
        return [
            'prefix' => 'admin',
            'plugin' => 'Shop',
            'controller' => 'ShopCategories',
            'action' => 'manage',
            $this->category->id,
        ];
    }

    public function getChildren()
    {
        return TableRegistry::get('Shop.ShopCategories')
            ->find()
            ->where(['parent_id' => $this->category->id])
            ->contain([])
            ->orderAsc('lft');
    }

    public function isPublished()
    {
        return $this->category->is_published;
    }

}