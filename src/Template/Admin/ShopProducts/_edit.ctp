<?php
$this->extend('Backend./Admin/Action/edit');

$this->loadHelper('Backend.FooTable');
$this->loadHelper('Media.Media');

//$this->Breadcrumbs->add(__d('shop', 'Shop Products'), ['action' => 'index']);
//$this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Product')));
?>
<div class="form">
<?= $this->Form->create($shopProduct); ?>
<?php echo $this->Form->input('type', ['options' => ['parent' => 'parent', 'child' => 'child', 'simple' => 'simple']]); ?>
<?= $this->Form->input('parent_id', ['options' => $parentShopProducts, 'empty' => '- No parent -']); ?>
    <?php if ($shopProduct->parent_id): ?>
        <?= $this->Html->link(
    __d('shop', 'Edit Parent'),
    ['action' => 'edit', $shopProduct->parent_id]); ?>
    <?php endif; ?>

<?php
//echo $this->Form->input('eav_attribute_set_id', ['options' => $attributeSets, 'empty' => true]);
echo $this->Form->input('title');
echo $this->Form->input('sku');
echo $this->Form->input('slug');
echo $this->Form->input('teaser_html', [
    'type' => 'htmleditor',
    'editor' => 'shop'
]);
echo $this->Form->input('desc_html', [
    'type' => 'htmleditor',
    'editor' => 'shop'
]);
//echo $this->Form->input('preview_preview_image_file');
//echo $this->Form->input('featured_preview_image_file');
//echo $this->Form->input('is_published');
//echo $this->Form->input('publish_start_date');
//echo $this->Form->input('publish_end_date');
//echo $this->Form->input('is_buyable');
echo $this->Form->input('price_net');
echo $this->Form->input('tax_rate');
echo $this->Form->input('price', ['readonly' => true]);
echo $this->Form->input('is_buyable');
//echo $this->Form->input('view_template');
?>
<?= $this->Form->fieldsetStart(['legend' => __d('shop','Advanced'), 'collapsed' => true]); ?>
<?php
echo $this->Form->input('shop_category_id', ['options' => $shopCategories, 'empty' => true]);
?>
<?php
echo $this->Form->input('view_template');
?>
<?= $this->Form->fieldsetEnd(); ?>
<?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'btn btn-primary']) ?>

<?= $this->Form->end() ?>
</div>



