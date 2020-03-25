<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Content\Model\Behavior\PageMeta\PageMetaTrait;

/**
 * ShopCategory Entity.
 */
class ShopCategory extends Entity
{
    use PageMetaTrait;

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

    /**
     * @var array
     */
    protected $_virtual = [
        'url', 'url_full',
    ];

    /**
     * @param null $for
     * @return \Cake\ORM\Query
     */
    public function getPath($for = null)
    {
        if ($for === null) {
            $for = $this->get('id');
        }

        return TableRegistry::getTableLocator()->get('Shop.ShopCategories')->find('path', ['for' => $for]);
    }

    /**
     * @return array
     */
    public function getViewUrl()
    {
        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'Categories',
            'action' => 'view',
            'category_id' => $this->id,
            //'category' => $this->slug,
            'category' => $this->url_path,
        ];
    }

    /**
     * @return array
     */
    public function getUrl()
    {
        return $this->getViewUrl();
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
     * Workaround hack to work as page
     * @return string
     */
    public function _getType()
    {
        return 'shop_category';
    }

    /**
     * @return \Cake\Datasource\EntityInterface|mixed
     */
    protected function _getParent()
    {
        if (
            !isset($this->_fields['parent_shop_category'])
            && isset($this->_fields['parent_id'])
            //&& !empty($this->_properties['parent_id'])
        ) {
            $this->parent_shop_category = TableRegistry::getTableLocator()->get('Shop.ShopCategories')->get($this->_fields['parent_id']);
        }

        return $this->parent_shop_category;
    }

    /**
     * @deprecated
     */
    protected function _getShopText($model, $id, $field, $locale = null)
    {
        $ShopTexts = TableRegistry::getTableLocator()->get('Shop.ShopTexts');

        return $ShopTexts->find()->where([
            'model' => $model,
            'model_id' => $id,
            'model_scope' => $field,
            'locale' => (string)($locale !== null) ? $locale : Configure::read('Shop.defaultLocale'),
        ])->first();
    }

    /**
     * @return array
     */
    protected function _getUrl()
    {
        return $this->getViewUrl();
    }

    /**
     * @return array
     */
    protected function _getUrlFull()
    {
        return Router::url($this->_getUrl(), true);
    }

    /**
     * @return array
     * @deprecated
     */
    protected function _getViewUrl()
    {
        return $this->getViewUrl();
    }

    /**
     * @return mixed
     */
    protected function _getUrlPath()
    {
        if (!isset($this->_fields['url_path'])) {
            $Table = TableRegistry::getTableLocator()->get('Shop.ShopCategories');
            $_path = "";
            $_categories = $Table->find('path', ['for' => $this->id])->toArray();
            //array_pop($_categories); // drop last element (this category)
            foreach ($_categories as $_category) {
                $_path .= '/' . $_category->slug;
            }
            $_path = ltrim($_path, '/');
            $this->_fields['url_path'] = $_path ?: false;
        }

        return $this->_fields['url_path'];
    }

    /**
     * @return \Cake\ORM\Query
     */
    protected function _getSubcategories()
    {
        return TableRegistry::getTableLocator()->get('Shop.ShopCategories')
            ->find('children', ['for' => $this->id, 'direct' => true, 'media' => true]);
            //->find('media')
    }

    /**
     * @return \Cake\ORM\Query
     */
    protected function _getPublishedSubcategories()
    {
        return TableRegistry::getTableLocator()->get('Shop.ShopCategories')
            ->find('all', ['media' => true])
            ->find('published')
            ->find('children', ['for' => $this->id, 'direct' => true])
            //->find('media')
            ->all();
    }

    /**
     * @return array
     */
    protected function _getProducts()
    {
        return TableRegistry::getTableLocator()->get('Shop.ShopProducts')
            ->find('all', ['media' => true])
            ->find('published')
            //->find('media')
            ->where(['shop_category_id' => $this->id, 'parent_id IS' => null])
            ->order(['title' => 'ASC'])
            ->toArray();
    }

    /**
     * @return array
     */
    protected function _getModules()
    {
        $contentModules = TableRegistry::getTableLocator()->get('Content.ContentModules')
            ->find()
            //->find('published')
            ->contain(['Modules'])
            ->where(['refscope' => 'Shop.ShopCategories', 'refid' => $this->id])
            ->all()
            ->toArray();

        $modules = [];
        foreach ($contentModules as $contentModule) {
            //$section = $contentModule->section;
            $modules[$contentModule->module->id] = $contentModule->module;
        }

        return $modules;
    }
}
