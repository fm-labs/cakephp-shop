<?php

namespace Shop\Model\Entity\Node;

use Cake\Datasource\EntityInterface;
use Content\Lib\ContentManager;
use Content\Model\Entity\Node;
use Content\Model\Entity\Post;
use Cake\Core\Configure;
use Shop\Model\Entity\ShopCategory;

class ShopCategoryNodeType extends Node\DefaultNodeType
{
    /**
     * @var ShopCategory
     */
    protected $shopCategory;

    public function setEntity(EntityInterface $entity)
    {
        parent::setEntity($entity);
        $this->shopCategory = ContentManager::getPostByType($entity->type, $entity->typeid);
    }

    public function getLabel()
    {
        $label = parent::getLabel();
        if (!$label) {
            $label = $this->shopCategory->name;
        }
        return $label;
    }

    public function getViewUrl()
    {
        return $this->shopCategory->getViewUrl();
    }

    public function getAdminUrl()
    {
        return $this->shopCategory->getAdminUrl();
    }

    public function getChildren()
    {
        $childNodes = parent::getChildren();

        if (empty($childNodes)) {
            $subCategories = $this->shopCategory->getChildren();
            foreach ($subCategories as $cat) {
                $childNodes[] = $cat->toNode();
            }
        }
        return $childNodes;
    }

    public function isHiddenInNav()
    {
        return (parent::isHiddenInNav() || !$this->shopCategory->isPublished()) ? true : false;
    }

}