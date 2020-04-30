<?php
$this->extend('Admin./Admin/Action/edit');

$this->loadHelper('Admin.FooTable');
$this->loadHelper('Media.Media');

//$this->Breadcrumbs->add(__d('shop', 'Shop Products'), ['action' => 'index']);
//$this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Product')));
?>
<div class="form">
<?= $this->Form->create($shopProduct); ?>
<?php echo $this->Form->control('type', ['options' => ['parent' => 'parent', 'child' => 'child', 'simple' => 'simple']]); ?>
<?= $this->Form->control('parent_id', ['options' => $parentShopProducts, 'empty' => '- No parent -']); ?>
    <?php if ($shopProduct->parent_id): ?>
        <?= $this->Html->link(
    __d('shop', 'Edit Parent'),
    ['action' => 'edit', $shopProduct->parent_id]); ?>
    <?php endif; ?>

<?php
//echo $this->Form->control('eav_attribute_set_id', ['options' => $attributeSets, 'empty' => true]);
echo $this->Form->control('title');
echo $this->Form->control('sku');
echo $this->Form->control('slug');
echo $this->Form->control('teaser_html', [
    'type' => 'htmleditor',
    'editor' => 'shop'
]);
echo $this->Form->control('desc_html', [
    'type' => 'htmleditor',
    'editor' => 'shop'
]);
//echo $this->Form->control('preview_preview_image_file');
//echo $this->Form->control('featured_preview_image_file');
//echo $this->Form->control('is_published');
//echo $this->Form->control('publish_start_date');
//echo $this->Form->control('publish_end_date');
//echo $this->Form->control('is_buyable');
echo $this->Form->control('price_net');
echo $this->Form->control('tax_rate');
echo $this->Form->control('price', ['readonly' => true]);
echo $this->Form->control('is_buyable');
//echo $this->Form->control('view_template');
?>
<?= $this->Form->fieldsetStart(['legend' => __d('shop','Advanced'), 'collapsed' => true]); ?>
<?php
echo $this->Form->control('shop_category_id', ['options' => $shopCategories, 'empty' => true]);
?>
<?php
echo $this->Form->control('view_template');
?>
<?= $this->Form->fieldsetEnd(); ?>
<?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'btn btn-primary']) ?>

<?= $this->Form->end() ?>
</div>



