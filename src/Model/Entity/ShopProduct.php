<?php
namespace Shop\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * ShopProduct Entity.
 */
class ShopProduct extends Entity
{

    use TranslateTrait;

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

    protected $_virtual = [
        'shop_category'
    ];

    /*
    public function getDescShort($locale = null)
    {
        return $this->_getShopText('Shop.ShopProducts', $this->id, 'desc_short_text', $locale);
    }

    public function getDescLong($locale = null)
    {
        return $this->_getShopText('Shop.ShopProducts', $this->id, 'desc_long_text', $locale);
    }
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

    protected function _getShopCategory()
    {
        if (!isset($this->_properties['shop_category'])) {

            $Table = TableRegistry::get('Shop.ShopCategories');
            $category = $Table
                ->find()
                ->where(['id' => $this->shop_category_id])
                ->contain([])
                ->first();

            $this->shop_category = $category;
        }

        return $this->_properties['shop_category'];
    }

    protected function _getUrl()
    {

        return [
            'prefix' => false,
            'plugin' => 'Shop',
            'controller' => 'ShopProducts',
            'action' => 'view',
            'product_id' => $this->id,
            'product' => $this->slug,
            'category' => ($this->shop_category) ? $this->shop_category->url_path : null,
            //$this->id
        ];
    }

    protected function _getPreviewImage()
    {
        return ($this->_properties['preview_image_file']) ?: $this->featured_image_file;
    }

    protected function _getShopProducts()
    {
        return TableRegistry::get('Shop.ShopProducts')
            ->find()
            ->where(['ShopProducts.shop_category_id' => $this->id, 'is_published' => true]);
    }

    protected function _getPrice()
    {
        $priceNet = $this->price_net;
        $taxRate = $this->tax_rate;

        return round($priceNet * (1 + ($taxRate / 100)), 2);
    }
}
