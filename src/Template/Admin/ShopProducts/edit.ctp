<?php

$this->loadHelper('Bootstrap.Tabs');
$this->Html->addCrumb(__d('shop', 'Shop Products'), ['action' => 'index']);
$this->Html->addCrumb(__d('shop', 'Edit {0}', __d('shop', 'Shop Product')));
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><?= h($shopProduct->title) ?> (<?= h((string) $locale); ?>)</strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <h4>Author</h4>
                    <span>Administrator</span>

                    <?php if ($shopProduct->shop_category): ?>
                        <h4><?= __d('shop','Category'); ?></h4>
                        <span><?= $this->Html->link(
                            $shopProduct->shop_category->name,
                            ['controller' => 'ShopCategories', 'action' => 'edit', $shopProduct->shop_category->id]
                        ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <h4>Publish state</h4>
                    <?= $this->Ui->statusLabel($shopProduct->is_published); ?>
                </div>
                <div class="col-md-4">
                    <h4>Languages</h4>
                    <?php foreach($this->get('locales') as $_locale => $_localeName): ?>
                        <?= $this->Html->link($_localeName, ['action' => 'edit', $shopProduct->id, 'locale' => $_locale]) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->Tabs->start(__d('shop','Edit {0}', __d('shop','Product'))); ?>
    <?php $this->Tabs->add(__d('shop','Edit {0}', __d('shop','Product'))); ?>

    <?= $this->Form->create($shopProduct); ?>
    <div class="row">
        <div class="col-md-9">
            <div class="users ui basic segment">
                <div class="ui form">
                    <?php
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
            </div>
        </div>
        <div class="col-md-3">
            <?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'btn btn-primary btn-block']) ?>

            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Publish')]); ?>
            <?php
            echo $this->Form->input('is_published');
            //echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
            //echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
            ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Shop Options')]); ?>
            <?php
            echo $this->Form->input('is_buyable');
            ?>
            <?= $this->Form->fieldsetEnd(); ?>


            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Media')]); ?>
            <?= $this->cell('Media.ImageSelect', [[
                'label' => 'Preview Image',
                'model' => 'Shop.ShopProducts',
                'id' => $shopProduct->id,
                'scope' => 'preview_image_file',
                'image' => $shopProduct->preview_image_file,
                'imageOptions' => ['width' => 200],
                'config' => 'shop'
            ]]); ?>


            <?= $this->cell('Media.ImageSelect', [[
                'label' => 'Featured Image',
                'model' => 'Shop.ShopProducts',
                'id' => $shopProduct->id,
                'scope' => 'featured_image_file',
                'image' => $shopProduct->featured_image_file,
                'imageOptions' => ['width' => 200],
                'config' => 'shop'
            ]]); ?>
            <?= $this->Form->fieldsetEnd(); ?>



            <?= $this->Form->fieldsetStart(['legend' => __d('shop','Advanced'), 'collapsed' => true]); ?>
            <?php
            echo $this->Form->input('view_template');
            ?>
            <?= $this->Form->fieldsetEnd(); ?>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <?php $this->Tabs->add(__d('shop','Attachments')); ?>

    <?php echo $this->cell('Backend.AttachmentsEditor', [
        'params' => [
            'model' => 'ShopProducts',
            'modelid' => $shopProduct->id,
            'scope' => 'media_images',
            'locale' => $locale
        ],
        'files' => $galleryList
    ]); ?>

    <?= $this->Tabs->render(); ?>


</div>