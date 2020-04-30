<?php
use Admin\View\Widget\ImageSelectWidget;
use Cake\Core\Configure;
use Cake\Routing\Router;
//$this->extend('base');

$this->loadHelper('Bootstrap.Tabs');

$this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Category')));
?>
<?php $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopCategory->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
)
?>
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
    <?php $this->Tabs->add(__d('shop', 'Category'), [
        'url' => ['action' => 'view', $shopCategory->id]
    ]); ?>


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
    <?php $this->Tabs->add(__d('shop', 'Debug')); ?>
    <?php debug($shopCategory); ?>
    <?php debug($shopCategory->toArray()); ?>


    <?php echo $this->Tabs->render(); ?>
</div>