<?php
use Backend\View\Widget\ImageSelectWidget;
use Cake\Core\Configure;
use Cake\Routing\Router;
?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<?php $this->loadHelper('Backend.TinyMce'); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Category'))); ?>
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

    <div class="form">

        <?= $this->Form->create($shopCategory, ['class' => 'no-ajax']); ?>

        <div class="actions">
            <div class="btn-group">
                <?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">

                <?= $this->Form->fieldsetStart(['legend' => __d('shop','General'), 'collapsed' => false]); ?>
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
                echo $this->Form->input('teaser_html', [
                    'type' => 'htmleditor',
                    'editor' => '@Shop.HtmlEditor.default'
                ]);
                echo $this->Form->input('desc_html', [
                    'type' => 'htmleditor',
                    'editor' => '@Shop.HtmlEditor.default'
                ]);
                ?>

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

                <?= $this->Form->fieldsetEnd(); ?>

                <?= $this->Form->fieldsetStart(['legend' => __d('shop','Layout'), 'collapsed' => true]); ?>
                <?= $this->Form->input('teaser_template'); ?>
                <?= $this->Form->input('view_template'); ?>
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
                <?= $this->cell('Media.ImageSelect', [[
                    'label' => 'Preview Image',
                    'model' => 'Shop.ShopCategories',
                    'id' => $shopCategory->id,
                    'scope' => 'preview_image_file',
                    'image' => $shopCategory->preview_image_file,
                    'imageOptions' => ['width' => 200],
                    'config' => 'shop'
                ]]); ?>


                <?= $this->cell('Media.ImageSelect', [[
                    'label' => 'Featured Image',
                    'model' => 'Shop.ShopCategories',
                    'id' => $shopCategory->id,
                    'scope' => 'featured_image_file',
                    'image' => $shopCategory->featured_image_file,
                    'imageOptions' => ['width' => 200],
                    'config' => 'shop'
                ]]); ?>
                <?= $this->Form->fieldsetEnd(); ?>

            </div>
        </div>


        <?= $this->Form->button(__d('shop', 'Save Changes')) ?>
        <?= $this->Form->end() ?>
    </div>

</div>