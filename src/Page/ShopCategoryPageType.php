<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/16/16
 * Time: 9:48 PM
 */

namespace Shop\Page;

use Cake\Controller\Controller;
use Content\Page\AbstractPageType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopCategory;

class ShopCategoryPageType extends AbstractPageType
{
    /**
     * @var ShopCategory
     */
    protected $category;

    public function setEntity(EntityInterface $page)
    {
        parent::setEntity($page);

        $categoryId = $page->redirect_location;
        $this->category = TableRegistry::get('Shop.ShopCategories')->get($categoryId);
    }

    function getUrl()
    {
        return $this->category->getPageUrl();
    }

    public function getAdminUrl()
    {
        return $this->category->getPageAdminUrl();
    }

    public function getChildren()
    {
        return $this->category->getPageChildren();
    }

    public function isPublished()
    {
        return $this->category->isPagePublished();
    }

    public function isHiddenInNav()
    {
        return $this->category->isPageHiddenInNav();
    }

    public function execute(Controller &$controller)
    {
    }
}