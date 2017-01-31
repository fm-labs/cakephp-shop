<?php
use Backend\View\Widget\ImageSelectWidget;
use Cake\Core\Configure;
use Cake\Routing\Router;
//$this->extend('base');

$this->loadHelper('Media.Media');
$this->loadHelper('Bootstrap.Tabs');

$this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Category')));
?>
<?= $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopCategory->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
)
?>
<!--
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
-->
<?= $this->Toolbar->addLink(
    __d('shop', 'Add {0}', __d('shop', 'Shop Category')),
    ['action' => 'add', 'parent_id' => $shopCategory->parent_id],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Add {0}', __d('shop', 'Sub Category')),
    ['action' => 'add', 'parent_id' => $shopCategory->id],
    ['data-icon' => 'plus']
) ?>
<!--
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
-->
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<?php $this->assign('title', $shopCategory->name); ?>
<div class="shop categories form">

    <div class="well">
        <?php $_locales = Configure::read('Shop.locales'); ?>
        <strong><?= __d('shop', '[{0}]', $_locales[$locale]); ?></strong> |
        <?php foreach($_locales as $_locale => $_localeName): ?>
            <?php if ($_locale === $locale) continue ;?>
            <?= $this->Html->link(__d('shop', 'Edit {0} version', $_localeName), ['action' => 'edit', $shopCategory->id, 'locale' => $_locale]) ?> |
        <?php endforeach; ?>
    </div>


    <?php $this->Tabs->create(); ?>
    <!-- General -->
    <?php $this->Tabs->add(__d('shop', 'General')); ?>

        <?= $this->Form->create($shopCategory); ?>
        <div class="row">
            <div class="col-md-9">

                <?php echo $this->Form->input('eav_attribute_set_id', ['options' => $attributeSets, 'empty' => true]); ?>

                <?= $this->Form->input('parent_id', ['options' => $parentShopCategories, 'empty' => '- No parent -']); ?>
                <!--
                <?php if ($shopCategory->parent_id): ?>
                    <?= $this->Html->link(
                        __d('shop', 'Manage Parent: {0}', $shopCategory->parent_shop_category->name),
                        ['action' => 'manage', $shopCategory->parent_id]); ?>
                <?php endif; ?>
                -->
                <?php
                echo $this->Form->input('name');
                echo $this->Form->input('slug');

                echo $this->Form->fieldsetStart(['legend' => __d('shop','Teaser'), 'collapsed' => true]);
                echo $this->Form->input('teaser_html', [
                    'type' => 'htmleditor',
                    'editor' => '@Shop.HtmlEditor.default'
                ]);
                echo $this->Form->fieldsetEnd();

                echo $this->Form->input('desc_html', [
                    'type' => 'htmleditor',
                    'editor' => '@Shop.HtmlEditor.default'
                ]);
                ?>


                <?= $this->Form->fieldsetStart(['legend' => __d('shop','Layout'), 'collapsed' => true]); ?>
                <?= $this->Form->input('teaser_template', ['empty' => __d('shop', 'Default')]); ?>
                <?= $this->Form->input('view_template', ['empty' => __d('shop', 'Default')]); ?>
                <?= $this->Form->fieldsetEnd(); ?>


                <?= $this->Form->fieldsetStart(['legend' => __d('shop','Advanced'), 'collapsed' => true]); ?>
                <?= $this->Form->input('is_alias'); ?>
                <?= $this->Form->input('alias_id', ['empty' => '- Not selected -', 'options' => $parentShopCategories]); ?>
                <?= $this->Form->fieldsetEnd(); ?>

                <?= $this->Form->fieldsetStart(['legend' => __d('shop','Tags'), 'collapsed' => true]); ?>
                <?php
                echo $this->Form->input('tags._ids', ['multiple' => 'checkbox']);
                ?>
                <?= $this->Form->fieldsetEnd(); ?>
            </div>
            <div class="col-md-3">

                <?= $this->Form->fieldsetStart([
                    'legend' => sprintf("%s %s", __d('shop','Published'), $this->Ui->statusLabel($shopCategory->is_published)),
                    'collapsed' => false
                ]); ?>
                <?php
                echo $this->Form->input('is_published');
                echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
                echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
                ?>
                <?= $this->Form->fieldsetEnd(); ?>


                <?= $this->Form->fieldsetStart(['legend' => __d('shop','Media'), 'collapsed' => false]); ?>
                <?= $this->Form->input('preview_image_file', ['type' => 'media_picker']); ?>
                <?= $this->Form->input('featured_image_file', ['type' => 'media_picker']); ?>
                <?= $this->Form->fieldsetEnd(); ?>

            </div>
        </div>

        <?= $this->Form->button(__d('shop', 'Save Changes')) ?>
        <?= $this->Form->end() ?>

    <!-- Related Custom Texts -->
    <?php $this->Tabs->add(__d('shop', 'Custom Texts')); ?>

        <?= $this->Form->create($shopCategory); ?>
        <?= $this->Form->fieldsetStart(['legend' => __d('shop','Custom Texts'), 'collapsed' => false]); ?>
        <?= $this->Form->input('custom_text1', [
            'type' => 'htmleditor',
            'label' => 'Related ll',
            'editor' => [
                'relative_urls' => false,
                'remove_script_host' => false,
                'convert_urls' => false,
            ]
        ]); ?>
        <?= $this->Form->input('custom_text2', [
            'type' => 'htmleditor',
            'label' => 'Related stone',
            'editor' => [
                'relative_urls' => false,
                'remove_script_host' => false,
                'convert_urls' => false,
            ]
        ]); ?>
        <?= $this->Form->button(__d('shop', 'Save Changes')) ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->end() ?>

    <!-- Related Attributes -->
    <?php $this->Tabs->add(__d('shop', 'Attributes')); ?>
    <?= $this->cell('Eav.AttributesFormInputs', [$shopCategory, 'Shop.ShopCategories']); ?>

    <!-- Related Products -->
    <?php $this->Tabs->add(__d('shop', 'Products'), [
        'url' => ['action' => 'relatedProducts', $shopCategory->id]
    ]); ?>

    <!-- Related HTML meta data -->
    <?php $this->Tabs->add('Meta', [
        'url' => ['action' => 'relatedPageMeta', $shopCategory->id]
    ]); ?>

    <!-- Related Content modules -->
    <?php $this->Tabs->add('Content Modules', [
        'url' => ['action' => 'relatedContentModules', $shopCategory->id]
    ]); ?>

    <!-- Debug -->
    <?php $this->Tabs->add(__d('shop', 'Debug')); ?>
    <?php debug($shopCategory); ?>
    <?php debug($shopCategory->toArray()); ?>


    <?php echo $this->Tabs->render(); ?>
</div>
<script>
    <?php
    $mediapicker = [
        'modal' => true,
        'treeUrl' => $this->Url->build(['plugin' => 'Media', 'controller' => 'MediaManager', 'action' => 'treeData', 'config' => 'shop', '_ext' => 'json']),
        'filesUrl' => $this->Url->build(['plugin' => 'Media', 'controller' => 'MediaManager', 'action' => 'filesData', 'config' => 'shop', '_ext' => 'json'])
    ];
    ?>
    $(document).ready(function() {
        $('.media-picker').mediapicker(<?= json_encode($mediapicker); ?>);
    });
</script>