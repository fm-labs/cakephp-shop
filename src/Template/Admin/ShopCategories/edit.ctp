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
<?php /* $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
    ['data-icon' => 'plus']
) */ ?>
<?php /* $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopCategory->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
) */
?>

<?php
/*
$this->Toolbar->addLink(
    __d('shop', 'Preview'),
    ['action' => 'preview', $shopCategory->id],
    ['data-icon' => 'search', 'target' => '_blank']
) */ ?>

<?php $this->assign('title', $shopCategory->name); ?>
<div class="shop categories form">

    <div style="margin: 0;" class="text-right">
        <?= __d('shop', 'Languages') ?>:
        <?php $_locales = Configure::read('Shop.locales'); ?>
        <?php foreach($_locales as $_locale => $_localeName): ?>
            <?php
            echo $this->Html->link(
                __d('shop', '{0}', $_localeName),
                ['action' => 'edit', $shopCategory->id, 'locale' => $_locale],
                ['data-locale' => $_locale]
            );

            if ($_locale === $this->get('locale')) {
                echo '(' . __d('shop', 'Current') . ')';
            }?>
        <?php endforeach; ?>
    </div>

    <?php $this->Tabs->create(); ?>
    <!-- General -->
    <?php $this->Tabs->add(__d('shop', 'General')); ?>

        <?= $this->Form->create($shopCategory, ['horizontal' => true]); ?>

                <?php //echo $this->Form->input('eav_attribute_set_id', ['options' => $attributeSets, 'empty' => true]); ?>

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

                //echo $this->Form->input('file1', ['type' => 'media_picker', 'config' => 'default']);
                //echo $this->Form->input('file2', ['type' => 'media_picker', 'config' => 'default']);

                echo $this->Form->fieldsetStart(['legend' => __d('shop','Teaser'), 'collapsed' => true]);
                echo $this->Form->input('teaser_html', [
                    'type' => 'htmleditor',
                    'editor' => 'shop'
                ]);
                echo $this->Form->fieldsetEnd();

                echo $this->Form->fieldsetStart(['legend' => __d('shop','Description'), 'collapsed' => false]);
                echo $this->Form->input('desc_html', [
                    'type' => 'htmleditor',
                    'editor' => 'shop'
                ]);
                echo $this->Form->fieldsetEnd();
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
                echo $this->Form->input('tags._ids', [
                    'type' => 'select',
                    'multiple' => true,
                    //'multiple' => 'checkbox',
                    'options' => $this->get('tagList', [])
                ]);
                ?>
                <?= $this->Form->fieldsetEnd(); ?>

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


                <?= $this->Form->fieldsetStart(['legend' => __d('shop','Media')]); ?>
                <?= $this->Form->input('preview_image_file', ['type' => 'media_picker', 'config' => 'shop']); ?>
                <?= $this->Form->input('featured_image_file', ['type' => 'media_picker', 'config' => 'shop']); ?>
                <?= $this->Form->fieldsetEnd(); ?>

        <?= $this->Form->button(__d('shop', 'Save Changes')) ?>
        <?= $this->Form->end() ?>

    <!-- Related Custom Texts -->
    <?php $this->Tabs->add(__d('shop', 'Custom Texts')); ?>

        <?= $this->Form->create($shopCategory, ['horizontal' => true]); ?>
        <?= $this->Form->fieldsetStart(['legend' => __d('shop','Custom Texts')]); ?>
        <?php for($i = 1; $i <= 5; $i++): ?>
            <?php
            $_field = sprintf('custom_text%d', $i);
            echo $this->Form->input($_field, [
            'type' => 'htmleditor',
            'label' => Configure::read('Shop.Admin.Categories.Labels.' . $_field),
            'editor' => ['lazy' => true]
        ]); ?>
        <?php endfor; ?>
        <?= $this->Form->button(__d('shop', 'Save Changes')) ?>
        <?= $this->Form->fieldsetEnd(); ?>
        <?= $this->Form->end() ?>

    <!-- Related Custom Files -->
    <?php $this->Tabs->add(__d('shop', 'Custom Files')); ?>

    <?= $this->Form->create($shopCategory, ['horizontal' => true]); ?>
    <?= $this->Form->fieldsetStart(['legend' => __d('shop','Custom Files')]); ?>
    <?php for($i = 1; $i <= 5; $i++): ?>
        <?php
        $_field = sprintf('custom_file%d', $i);
        echo $this->Form->input($_field, [
            'type' => 'media_picker',
            'config' => 'default',
            'label' => Configure::read('Shop.Admin.Categories.Labels.' . $_field),
        ]); ?>
    <?php endfor; ?>
    <?= $this->Form->button(__d('shop', 'Save Changes')) ?>
    <?= $this->Form->fieldsetEnd(); ?>
    <?= $this->Form->end() ?>

    <!-- Related Attributes -->
    <?php // $this->Tabs->add(__d('shop', 'Attributes')); ?>
    <?php // echo $this->cell('Eav.AttributesFormInputs', [$shopCategory, 'Shop.ShopCategories']); ?>

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
    <?php //$this->Tabs->add(__d('shop', 'Debug')); ?>
    <?php //debug($shopCategory); ?>
    <?php //debug($shopCategory->toArray()); ?>


    <?php echo $this->Tabs->render(); ?>
</div>