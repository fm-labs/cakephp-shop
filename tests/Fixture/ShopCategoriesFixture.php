<?php
namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopCategoriesFixture
 *
 */
class ShopCategoriesFixture extends TestFixture
{
    /**
     * Import
     *
     * @var array
     */
    ////public $import = ['table' => 'shop_categories'];

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'lft' => 1,
            'rght' => 2,
            'parent_id' => null,
            'level' => 1,
            'name' => 'Test Root Category',
            'slug' => 'test-root-category',
            'teaser_html' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'desc_html' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'preview_image_file' => '',
            'featured_image_file' => '',
            'image_files' => '',
            'teaser_template' => '',
            'view_template' => '',
            'is_published' => 1,
            'is_alias' => 0,
            'alias_id' => null,
        ],
    ];
}
