<div class="debug panel panel-default">

    <div class="panel-heading">
        Debug
    </div>
    <div class="panel-body">
    <?= $this->cell('Admin.EntityView', [ $shopCategory ], [
        'title' => false,
        'model' => 'Shop.ShopCategories',
        'fields' => [
            'name' => [
                'formatter' => function($val, $entity) {
                    return $this->Html->link($val, $entity->getViewUrl(), ['class' => 'link-frame']);
                }
            ],
            'parent_id' => [
                'title' => __d('shop','Parent Category'),
                'formatter' => function($val, $entity) {
                    if (!$entity->parent_id) {
                        return '-';
                    }

                    $title = ($entity->parent_shop_category->name) ? $entity->parent_shop_category->name : $entity->parent_id;
                    return $this->Html->link($entity->parent_shop_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $entity->parent_shop_category->id]);
                }
            ],
            'is_published' => ['formatter' => 'boolean'],
            'url' => ['formatter' => 'link'],
            'preview_image_file' => [
                'formatter' => function($val, $entity) {
                    return (is_object($val)) ? $this->Media->thumbnail($val->filepath, ['width' => 100, 'height' => 100]) : $val;
                }
            ],
            'featured_image_file' => [
                'formatter' => function($val) {
                    return (is_object($val)) ? $this->Media->thumbnail($val->filepath, ['width' => 100, 'height' => 100]) : $val;
                }
            ]
        ],
        'exclude' => ['id', 'level', 'lft', 'rght', 'parent_shop_category', 'child_shop_categories']
    ]); ?>



        <?php if ($shopCategory->shop_products): ?>
    <?php foreach ((array) $shopCategory->shop_products as $shopProduct): ?>
        <div>

            <?= $this->cell('Admin.EntityView', [ $shopProduct ], [
                'title' => 'Product: ' . $shopProduct->title,
                'model' => 'Shop.ShopProducts'
            ]); ?>
        </div>
    <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>