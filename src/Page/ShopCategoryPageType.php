<?php

namespace Shop\Page;

use Banana\Menu\MenuItem;
use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Content\Model\Entity\Page;
use Content\Page\AbstractPageType;
use Shop\Model\Entity\ShopCategory;

/**
 * Class ShopCategoryPageType
 * @package Shop\Page
 */
class ShopCategoryPageType extends AbstractPageType
{

    /**
     * @param EntityInterface $entity
     * @return string
     */
    public function getLabel(EntityInterface $entity)
    {
        $label = null;
        if ($entity instanceof Page) {
            $label = $entity->title;
        } elseif ($entity instanceof ShopCategory) {
            $label = $entity->name;
        }
        return $label;
    }

    /**
     * {@inheritDoc}
     */
    public function findChildren(EntityInterface $entity)
    {
        if ($entity instanceof Page) {
            $categoryId = $entity->redirect_location;

            return TableRegistry::get('Shop.ShopCategories')
                ->find()
                ->where(['parent_id' => $categoryId])
                ->contain([])
                ->orderAsc('lft')
                ->all();
        }

        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function toMenuItem(EntityInterface $entity, $maxDepth = 1)
    {
        if ($entity instanceof Page) {
            $categoryId = $entity->redirect_location;
            $category = TableRegistry::get('Shop.ShopCategories')->get($categoryId);
        } elseif ($entity instanceof ShopCategory) {
            $category = $entity;
        }
        $title = $this->getLabel($entity);
        $url = $this->toUrl($category);

        $item = new MenuItem($title, $url);
        return $item;
    }

    /**
     * {@inheritDoc}
     */
    public function toUrl(EntityInterface $entity)
    {
        if ($entity instanceof Page) {
            $categoryId = $entity->redirect_location;
            $category = TableRegistry::get('Shop.ShopCategories')->get($categoryId);
            return $category->getViewUrl();
        } elseif ($entity instanceof ShopCategory) {
            return $entity->getViewUrl();
        }
    }

    public function isEnabled(EntityInterface $entity)
    {
        if ($entity instanceof Page) {
            $categoryId = $entity->redirect_location;
            $category = TableRegistry::get('Shop.ShopCategories')->get($categoryId);
            return $category->is_published;
        } elseif ($entity instanceof ShopCategory) {
            return $entity->is_published;
        }
        return false;
    }

    public function execute(Controller &$controller, EntityInterface $entity)
    {
    }
}
