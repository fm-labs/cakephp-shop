<?php

namespace Shop\Menu;

use Content\Lib\ContentManager;
use Content\Menu\BaseMenuHandler;
use Content\Menu\MenuHandlerInterface;
use Content\Model\Entity\MenuItem;
use Content\Model\Entity\Post;
use Cake\Core\Configure;

class ShopCategoryMenuHandler extends BaseMenuHandler implements MenuHandlerInterface
{
    /**
     * @var Post
     */
    protected $shopCategory;

    public function __construct(MenuItem $item)
    {
        parent::__construct($item);
        $this->shopCategory = ContentManager::getPostByType($item->type, $item->typeid);
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
        $childMenuItems = parent::getChildren();

        if (empty($childMenuItems)) {
            $subCategories = $this->shopCategory->getChildren();
            foreach ($subCategories as $cat) {
                $childMenuItems[] = $cat->toMenuItem();
            }
        }
        return $childMenuItems;
    }

    public function isHiddenInNav()
    {
        return !$this->shopCategory->isPublished();
    }

}