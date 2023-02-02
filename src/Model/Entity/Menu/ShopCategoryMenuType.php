<?php
declare(strict_types=1);

namespace Shop\Model\Entity\Menu;

use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Content\Model\Entity\Menu\AbstractMenuType;
use Cupcake\Menu\MenuItem;

/**
 * Class ContentPageType
 *
 * @package Content\Model\Entity\Menu
 */
class ShopCategoryMenuType extends AbstractMenuType
{
    use InstanceConfigTrait;

    protected $_defaultConfig = [
        'title' => null,
        'shop_category_id' => null,
        'shop_subcategories_depth' => -1, // -1 = All, 0 = None, 1 = 1 level, etc...
        'hide_in_nav' => false,
        'hide_in_sitemap' => false,
    ];

    /**
     * @var \Shop\Model\Entity\ShopCategory
     */
    protected $_category;

    public function __construct(EntityInterface $entity)
    {
        parent::__construct($entity);
        $this->_category = $this->_getShopCategory($this->getConfig('shop_category_id'));
    }

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        $label = $this->getConfig('title');
        if (!$label) {
            $label = $this->_category->get('name');
        }

        return $label;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {
        return $this->_buildUrl($this->_category);
    }

    /**
     * {@inheritDoc}
     */
    public function getPermaUrl()
    {
        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'Shop',
            'action' => 'index',
            '?' => ['c' => $this->getConfig('shop_category_id')],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function isVisibleInMenu()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isVisibleInSitemap()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function toMenuItem($maxDepth = 0)
    {
        $item = new MenuItem(
            $this->getLabel(),
            $this->getUrl()
        );

        // inject child shop categories
        //$depth = min($maxDepth, $this->getConfig('shop_subcategories_depth'));
        $depth = $this->getConfig('shop_subcategories_depth');
        if ($depth > 0) {
            $children = TableRegistry::getTableLocator()->get('Shop.ShopCategories')
                ->find()
                ->where(['parent_id' => $this->getConfig('shop_category_id')])
                ->contain([])
                ->orderAsc('lft')
                ->all();

            foreach ($children as $child) {
                //$childType = new ShopCategoryMenuType(['shop_category_id' => $child->id, 'shop_subcategories_depth' => $depth - 1]);
                //$childItem = $childType->toMenuItem();
                $childItem = new MenuItem($child->name, $this->_buildUrl($child));
                $item->addChild($childItem);
            }
        }

        return $item;
    }

    protected function _buildUrl(EntityInterface $category)
    {
        if (\Cake\Core\Configure::read('Shop.Router.enablePrettyUrls') && $category->get('slug')) {
            return [
                'prefix' => false,
                'plugin' => 'Shop',
                'controller' => 'Categories',
                'action' => 'view',
                'category' => $category->get('slug'),
                'category_id' => $category->get('id'),
            ];
        }

        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'Categories',
            'action' => 'view',
            'id' => $category->get('id'),
        ];
    }

    /**
     * @param int $id Shop Category ID
     * @return \Shop\Model\Entity\ShopCategory
     */
    protected function _getShopCategory($id)
    {
        return TableRegistry::getTableLocator()->get('Shop.ShopCategories')->get($id, ['contain' => []]);
    }
}
