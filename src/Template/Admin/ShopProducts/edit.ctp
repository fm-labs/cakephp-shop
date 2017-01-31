<?php
$this->loadHelper('Bootstrap.Tabs');
$this->loadHelper('Media.Media');

$this->Breadcrumbs->add(__d('shop', 'Shop Products'), ['action' => 'index']);
$this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Product')));
?>
<?= $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopProduct->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopProduct->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopProduct->shop_category_id],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<?php $this->assign('title', $shopProduct->title); ?>
<div class="form">
    <dl class="dl-horizontal">
        <dt>Category</dt>
        <dd>
            <?php if ($shopProduct->shop_category): ?>
                 <?= $this->Html->link(
                        $shopProduct->shop_category->name,
                        ['controller' => 'ShopCategories', 'action' => 'edit', $shopProduct->shop_category->id]
                    ); ?>
            <?php endif; ?>
        </dd>
        <dt>Published</dt>
        <dd><?= $this->Ui->statusLabel($shopProduct->is_published); ?></dd>
        <dt>Languages</dt>
        <dd>
            <?php foreach($this->get('locales') as $_locale => $_localeName): ?>
                <?= $this->Html->link($_localeName, ['action' => 'edit', $shopProduct->id, 'locale' => $_locale]) ?>
            <?php endforeach; ?>
        </dd>
    </dl>

    <?php $this->Tabs->start(__d('shop','Edit {0}', __d('shop','Product'))); ?>
    <?php $this->Tabs->add(__d('shop','Edit {0}', __d('shop','Product'))); ?>

    <?= $this->Form->create($shopProduct); ?>
    <div class="row">
        <div class="col-md-9">
            <?php
            //echo $this->Form->input('eav_attribute_set_id', ['options' => $attributeSets, 'empty' => true]);
            echo $this->Form->input('shop_category_id', ['options' => $shopCategories, 'empty' => true]);
            echo $this->Form->input('sku');
            echo $this->Form->input('title');
            echo $this->Form->input('slug');
            echo $this->Form->input('teaser_html', [
                'type' => 'htmleditor',
                'editor' => '@Shop.HtmlEditor.default'
            ]);
            echo $this->Form->input('desc_html', [
                'type' => 'htmleditor',
                'editor' => '@Shop.HtmlEditor.default'
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
            <?= $this->Form->button(__d('shop', 'Submit')) ?>
        </div>
        <div class="col-md-3">
            <?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'btn btn-primary btn-block']) ?>

            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Publish')]); ?>
            <?php
            echo $this->Form->input('is_buyable');
            echo $this->Form->input('is_published');
            echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
            echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
            ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Media')]); ?>
            <?= $this->Form->input('preview_image_file', ['type' => 'media_picker']); ?>
            <?= $this->Form->input('featured_image_file', ['type' => 'media_picker']); ?>
            <?= $this->Form->fieldsetEnd(); ?>



            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Advanced'), 'collapsed' => true]); ?>
            <?php
            echo $this->Form->input('view_template');
            ?>
            <?= $this->Form->fieldsetEnd(); ?>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <?php // $this->Tabs->add(__d('shop','Attachments')); ?>


    <?php /** echo $this->cell('Backend.AttachmentsEditor', [
        'params' => [
            'model' => 'ShopProducts',
            'modelid' => $shopProduct->id,
            'scope' => 'media_images',
            'locale' => $locale
        ],
        'files' => $galleryList
    ]); **/?>

    <!-- Attributes -->
    <?php //$this->Tabs->add(__d('shop', 'Attributes')); ?>
    <?php //echo $this->cell('Eav.AttributesFormInputs', [$shopProduct, 'Shop.ShopProducts']); ?>

    <!-- Debug -->
    <?php $this->Tabs->add(__d('shop', 'Debug')); ?>
    <?php debug($shopProduct); ?>
    <?php debug($shopProduct->toArray()); ?>

    <?= $this->Tabs->render(); ?>


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
