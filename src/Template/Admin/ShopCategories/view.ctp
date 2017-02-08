<?php
use Cake\Core\Configure;

$this->loadHelper('Bootstrap.Tabs');
$this->loadHelper('Media.Media');
?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopCategory->name); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Edit {0}', __d('shop', 'Shop Category')),
    ['action' => 'edit', $shopCategory->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Delete {0}', __d('shop', 'Shop Category')),
    ['action' => 'delete', $shopCategory->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Category')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="shopCategories view">

    <div class="actions text-right">
        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $shopCategory->id], ['class' => 'btn btn-primary btn-edit']); ?>
    </div>

    <?= $this->cell('Backend.EntityView', [ $shopCategory ], [
        'title' => false,
        'model' => 'Shop.ShopCategories',
        'fields' => [
            'title' => [
                'formatter' => function($val, $entity) {
                    return $this->Html->link($val, ['action' => 'edit', $entity->id], ['class' => 'link-frame']);
                }
            ],
            'parent_id' => [
                'title' => __d('shop','Parent Category'),
                'formatter' => function($val, $entity) {
                    if (!$entity->parent_id) {
                        return __d('shop','Root Page');
                    }

                    $title = ($entity->parent_shop_category->name) ? $entity->parent_shop_category->name : $entity->parent_id;
                    return $this->Html->link($entity->parent_shop_category->name, ['controller' => 'ShopCategories', 'action' => 'view', $entity->parent_shop_category->id]);
                }
            ],
            'is_published' => ['formatter' => 'boolean'],
            'url' => ['formatter' => 'link'],
            'teaser_html' => ['formatter' => 'html'],
            'desc_html' => ['formatter' => 'html'],
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
</div>
