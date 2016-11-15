<?php
namespace Shop\Model\Entity;

use Content\Model\Behavior\PageMeta\PageMetaTrait;
use Content\Model\Entity\MenuItem;
use Content\Model\Entity\Page\PageInterface;
use Content\Model\Entity\PageTypeTrait;
use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Content\Post\PostHandlerEntityTrait;

/**
 * ShopCategory Entity.
 */
class ShopCategory extends Entity implements PageInterface
{
    use PageMetaTrait;
    //use PageTypeTrait;
    use PostHandlerEntityTrait;

    /**
     * @var string PageMetaTrait model definition
     */
    protected $_pageMetaModel = 'Shop.ShopCategories';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     * Note that '*' is set to true, which allows all unspecified fields to be
     * mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _getType()
    {
        return 'shop_category';
    }

    /**
     * @return MenuItem
     */
    public function toMenuItem()
    {
        return TableRegistry::get('Content.MenuItems')->newEntity([
            'title' => $this->name,
            'type' => 'shop_category',
            'typeid' => $this->id,
        ]);
    }

    public function getPath($for = null)
    {
        if ($for === null) {
            $for = $this->get('id');
        }

        return TableRegistry::get('Shop.ShopCategories')->find('path', ['for' => $for]);
    }

    protected function _getParent()
    {
        if (!isset($this->_properties['parent_shop_category'])
            && isset($this->_properties['parent_id'])
            //&& !empty($this->_properties['parent_id'])
        ) {
            $this->parent_shop_category = TableRegistry::get('Shop.ShopCategories')->get($this->_properties['parent_id']);
        }
        return $this->parent_shop_category;
    }

    /**
     * @deprecated Use teaser_html property instead
     */
    public function getDescShort($locale = null)
    {
        //return $this->_getShopText('Shop.ShopCategories', $this->id, 'desc_short_text', $locale);
        return $this->teaser_html;
    }

    /**
     * @deprecated Use desc_html property instead
     */
    public function getDescLong($locale = null)
    {
        //return $this->_getShopText('Shop.ShopCategories', $this->id, 'desc_long_text', $locale);
        return $this->desc_html;
    }

    /**
     * @deprecated
     */
    protected function _getShopText($model, $id, $field, $locale = null)
    {
        $ShopTexts = TableRegistry::get('Shop.ShopTexts');
        return $ShopTexts->find()->where([
            'model' => $model,
            'model_id' => $id,
            'model_scope' => $field,
            'locale' => (string) ($locale !== null) ? $locale : Configure::read('Shop.defaultLocale')
        ])->first();
    }

    protected function _getUrlPath()
    {
        if (!isset($this->_properties['url_path'])) {
            $Table = TableRegistry::get('Shop.ShopCategories');
            $_path = "";
            $_categories = $Table->find('path', ['for' => $this->id])->toArray();
            //array_pop($_categories); // drop last element (this category)
            foreach ($_categories as $_category) {
                $_path .= '/' . $_category->slug;
            }
            $_path = ltrim($_path, '/');
            $this->_properties['url_path'] = ($_path) ?: false;
        }

        return $this->_properties['url_path'];
    }

    /**
     * @return array
     * @deprecated Use getPageUrl() instead
     */
    protected function _getUrl()
    {
        return $this->getPageUrl();
    }

    protected function _getPermaUrl()
    {
        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'ShopCategories',
            'action' => 'view',
            $this->id
        ];
    }

    protected function _getSubcategories()
    {
        return TableRegistry::get('Shop.ShopCategories')
            ->find('children', ['for' => $this->id, 'direct' => true])
            ->find('media');
    }

    protected function _getPublishedSubcategories()
    {
        return TableRegistry::get('Shop.ShopCategories')
            ->find('published')
            ->find('children', ['for' => $this->id, 'direct' => true])
            ->find('media');
    }

    protected function _getProducts()
    {
        return TableRegistry::get('Shop.ShopProducts')
            ->find('published')
            ->find('media')
            ->where(['shop_category_id' => $this->id])
            ->toArray();
    }


    /** PAGE AWARE **/

    /**
     * @deprecated
     */
    public function getPageId() {
        return $this->id;
    }

    /**
     * @deprecated
     */
    public function getPageTitle()
    {
        return $this->name;
    }


    /**
     * @deprecated
     */
    public function getPageType()
    {
        return 'shop_category';
    }

    /**
     * @deprecated
     */
    public function getPageUrl()
    {
        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'ShopCategories',
            'action' => 'view',
            'category_id' => $this->id,
            //'category' => $this->slug,
            'category' => $this->url_path,
        ];
    }

    /**
     * @deprecated
     */
    public function getPageAdminUrl()
    {
        return [
            'prefix' => 'admin',
            'plugin' => 'Shop',
            'controller' => 'ShopCategories',
            'action' => 'manage',
            $this->id,
        ];
    }

    /**
     * @deprecated
     */
    public function getPageChildren()
    {
        return TableRegistry::get('Shop.ShopCategories')
            ->find()
            ->where(['parent_id' => $this->id])
            ->contain([])
            ->orderAsc('lft')
            ->all();
    }

    /**
     * @deprecated
     */
    public function isPagePublished()
    {
        return $this->is_published;
    }

    /**
     * @deprecated
     */
    public function isPageHiddenInNav()
    {
        return null;
    }
}
