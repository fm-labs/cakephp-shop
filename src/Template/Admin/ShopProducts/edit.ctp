<?php
$this->loadHelper('Bootstrap.Tabs');
$this->loadHelper('Media.Media');

$this->Breadcrumbs->add(__d('shop', 'Shop Products'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Product')));
?>
<?php $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopProduct->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopProduct->id)]
)
?>
<?php $this->assign('title', $shopProduct->title); ?>
<div class="form">


    <?php $this->Tabs->start(__d('shop','Edit {0}', __d('shop','Product'))); ?>
    <?php $this->Tabs->add(__d('shop','Edit {0}', __d('shop','Product'))); ?>

    <?= $this->Form->create($shopProduct); ?>
    <div class="row">
        <div class="col-md-9">

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
            //echo $this->Form->input('view_template');
            ?>
            <?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="col-md-3">

            <?= $this->Form->fieldsetStart(__d('shop','Languages')); ?>
            <?php foreach($this->get('locales') as $_locale => $_localeName): ?>
                <?= $this->Html->link($_localeName, ['action' => 'edit', $shopProduct->id, 'locale' => $_locale]) ?>
            <?php endforeach; ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Shop Category')]); ?>
            <?php
            echo $this->Form->input('shop_category_id', ['options' => $shopCategories, 'empty' => true]);
            ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Publish')]); ?>
            <?php
            echo $this->Form->input('is_buyable');
            echo $this->Form->input('is_published');
            echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
            echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
            ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Media')]); ?>
            <?= $this->Form->input('preview_image_file', ['type' => 'media_picker', 'config' => 'shop']); ?>
            <?= $this->Form->input('featured_image_file', ['type' => 'media_picker', 'config' => 'shop']); ?>
            <?= $this->Form->fieldsetEnd(); ?>



            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Advanced'), 'collapsed' => true]); ?>
            <?php
            echo $this->Form->input('view_template');
            ?>
            <?= $this->Form->fieldsetEnd(); ?>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <?php $this->Tabs->add(__d('shop','Product versions'), ['url' => ['action' => 'relatedProducts', $shopProduct->id]]); ?>

    <!-- Attributes -->
    <?php //$this->Tabs->add(__d('shop', 'Attributes')); ?>
    <?php //echo $this->cell('Eav.AttributesFormInputs', [$shopProduct, 'Shop.ShopProducts']); ?>

    <!-- Debug -->
    <?php $this->Tabs->add(__d('shop', 'Debug')); ?>
    <?php debug($shopProduct); ?>
    <?php debug($shopProduct->toArray()); ?>

    <?= $this->Tabs->render(); ?>


</div>