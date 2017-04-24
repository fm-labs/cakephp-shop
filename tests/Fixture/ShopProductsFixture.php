<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopProductsFixture
 *
 */
class ShopProductsFixture extends TestFixture
{

    /**
     * Import
     *
     * @var array
     */
    public $import = ['table' => 'shop_products'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'parent_id' => null,
            'type' => 'simple',
            'shop_category_id' => 1,
            'sku' => '',
            'title' => 'Test Item 100',
            'slug' => 'test-item-100',
            'teaser_html' => '',
            'desc_html' => '<h2>Test Item 100</h2><p>100 Bucks</p>',
            'preview_image_file' => null,
            'featured_image_file' => null,
            'image_files' => null,
            'is_published' => true,
            'publish_start_date' => '2015-08-16 00:00:00',
            'publish_end_date' => '2015-08-16 00:00:00',
            'is_buyable' => true,
            'priority' => null,
            'price' => 120,
            'price_net' => 100,
            'tax_rate' => 20,
            'meta_keywords' => null,
            'meta_description' => null,
            'custom1' => '131',
            'custom2' => null,
            'custom3' => null,
            'custom4' => null,
            'custom5' => null,
            'view_template' => '',
            'modified' => null,
            'created' => null
        ],
    ];
}
