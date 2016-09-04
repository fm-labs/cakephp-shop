<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 5/16/16
 * Time: 9:48 PM
 */

namespace Shop\Page;

use Banana\Model\Entity\Page;
use Banana\Page\AbstractPageType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Shop\Model\Entity\ShopCategory;

class ShopCategoryPageType extends AbstractPageType
{
    /**
     * @var ShopCategory
     */
    protected $category;

    public function __construct(Page $page)
    {
        parent::__construct($page);

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

}