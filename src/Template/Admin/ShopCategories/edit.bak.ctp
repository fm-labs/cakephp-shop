<?php
use Backend\View\Widget\ImageSelectWidget;
use Cake\Core\Configure;
use Cake\Routing\Router;
?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Shop Categories'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'Edit {0}', __d('shop', 'Shop Category'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopCategory->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
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
<?php $this->Toolbar->startGroup('More'); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Parent Shop Categories')),
    ['controller' => 'ShopCategories', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Parent Shop Category')),
    ['controller' => 'ShopCategories', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<?php $this->assign('title', '[ShopCategory] ' . $shopCategory->name); ?>
<div class="shop categories form">

    <h2 class="ui header">
        <i class="edit"></i>
        <?= __d('shop', 'Edit {0}: {2} [{1}]', __d('shop', 'Shop Category'), (string) $locale, $shopCategory->name) ?>
    </h2>
    <div style="overflow: visible;">
        Url: <?= $this->Html->link($shopCategory->url); ?> |
        PermaUrl: <?= $this->Html->link($shopCategory->permaUrl); ?>
    </div>
    <div class="ui divider"></div>

    <div class="ui three column grid">
        <div class="column">
            <h5><?= __d('shop','Author'); ?></h5>
            <span>Administrator</span>
        </div>
        <div class="column">
            <h5><?= __d('shop','Published'); ?></h5>
            <?= $this->Ui->statusLabel($shopCategory->is_published); ?>
        </div>
        <div class="column">
            <h5><?= __d('shop','Languages'); ?></h5>
            <ul>
                <?php foreach(Configure::read('Shop.locales') as $_locale => $_localeName): ?>
                    <li><?= $this->Html->link($_localeName, ['action' => 'edit', $shopCategory->id, 'locale' => $_locale]) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="ui divider"></div>

    <?php $this->Tabs->start(); ?>
    <?php $this->Tabs->add(__d('shop','Edit {0}', __d('shop','Shop Category'))); ?>



    <div class="form">
    <?= $this->Form->create($shopCategory); ?>
    <div class="ui grid">
        <div class="row">
            <div class="twelve wide column">
                <div class="ui form">
                    <?php
                    echo $this->Form->input('name');
                    echo $this->Form->input('slug');
                    echo $this->Form->input('teaser_html', [
                        'type' => 'htmleditor',
                        'editor' => [
                            'image_list_url' => '@Content.HtmlEditor.shop.imageList',
                            'link_list_url' => '@Content.HtmlEditor.shop.linkList'
                        ]
                    ]);
                    echo $this->Form->input('desc_html', [
                        'type' => 'htmleditor',
                        'editor' => [
                            'image_list_url' => '@Content.HtmlEditor.shop.imageList',
                            'link_list_url' => '@Content.HtmlEditor.shop.linkList'
                        ]
                    ]);
                    echo $this->Form->input('is_published');
                    echo $this->Form->input('tags._ids', ['multiple' => 'checkbox']);
                    ?>

                </div>

                <div class="ui hidden divider"></div>


            </div>
            <div class="four wide column">
                <h5 class="ui top attached header">Preview Image</h5>
                <div class="ui attached segment form">
                    <?php if ($shopCategory->preview_image_file): ?>
                        <?= $this->Html->image($shopCategory->preview_image_file->url, ['width' => 200]); ?>
                    <?php endif; ?>
                    <?php
                    //echo $this->Form->input('preview_image_file',
                    //    ['type' => 'imageselect', 'options' => $galleryList, 'empty' => __d('shop', '- No image -')]);
                    ?>
                </div>

                <h5 class="ui top attached header">Featured Image</h5>
                <div class="ui attached segment form">
                    <?php if ($shopCategory->featured_image_file): ?>
                        <?= $this->Html->image($shopCategory->featured_image_file->url, ['width' => 200]); ?>
                    <?php endif; ?>
                    <?php
                    //echo $this->Form->input('featured_image_file',
                    //    ['type' => 'imageselect', 'options' => $galleryList, 'empty' => __d('shop', '- No image -')]);
                    ?>
                </div>

                <h5 class="ui top attached header">Structure</h5>
                <div class="ui attached segment form">
                    <?= $this->Form->input('parent_id', ['options' => $parentShopCategories, 'empty' => '- No parent -']); ?>
                    <?php if ($shopCategory->parent_id): ?>
                    <?= $this->Html->link(
                            __d('shop', 'Edit Parent: {0}', $shopCategory->parent_shop_category->name),
                            ['action' => 'edit', $shopCategory->parent_id]); ?>
                    <?php endif; ?>
                </div>

                <h5 class="ui top attached header">Layout</h5>
                <div class="ui attached segment form">
                    <?= $this->Form->input('teaser_template'); ?>
                    <?= $this->Form->input('view_template'); ?>
                </div>


                <h5 class="ui top attached header">Advanced</h5>
                <div class="ui attached segment form">
                    <?= $this->Form->input('is_alias'); ?>
                    <?= $this->Form->input('alias_id', ['empty' => '- Not selected -', 'options' => $parentShopCategories]); ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>
    </div>

    <div class="ui divider"></div>

    <?php $this->Tabs->add(__d('shop', 'Images')); ?>

    <!-- IMAGE FORM -->
    <div class="ui form">
        <h3 class="ui header">Images</h3>
        <?php
        echo $this->Form->create($shopCategory);
        echo $this->Form->input('preview_image_file',
            ['type' => 'imageselect', 'options' => $galleryList, 'empty' => __d('shop', '- No image -')]);
        echo $this->Form->input('featured_image_file',
            ['type' => 'imageselect', 'options' => $galleryList, 'empty' => __d('shop', '- No image -')]);
        echo $this->Form->submit();
        echo $this->Form->end();
        ?>
    </div>


    <h5>Preview Image</h5>
    <?= $this->element('Backend.Attachment/image', [
        'image' => $shopCategory->preview_image_file
    ]); ?>

    <h5>Featured Image</h5>
    <?= $this->element('Backend.Attachment/image', [
        'image' => $shopCategory->featured_image_file
    ]); ?>

    <!-- TABS -->
    <div class="ui divider"></div>


    <?php $this->Tabs->add(__d('shop', 'Related Products')); ?>

    <h3>Related Products</h3>
    <table class="ui compact table">
        <thead>
        <tr>
            <th>Sku</th>
            <th>Title</th>
            <th class="actions">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($shopCategory->shop_products as $product): ?>
            <tr>
                <td><?= h($product->sku); ?></td>
                <td><?= h($product->title); ?></td>
                <td class="actions">
                    <?= $this->Ui->link('Edit',
                        ['controller' => 'ShopProducts', 'action' => 'edit', $product->id],
                        ['class' => 'ui mini button', 'data-icon' => 'edit']
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="actions">
        <?= $this->Ui->link('Add Product',
            ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
            ['class' => 'ui button', 'data-icon' => 'plus']
        ); ?>
    </div>


    <?php $this->Tabs->add('Related Content Modules'); ?>
    <!-- RELATED CONTENT MODULES -->
    <h3>Related content modules</h3>
    <?= $this->element('Content.Admin/Content/related_content_modules', ['content' => $shopCategory]); ?>
    <br />
    <?= $this->Ui->link('Build a new module for this shop category', [
        'controller' => 'ModuleBuilder',
        'action' => 'build2',
        'refscope' => 'Shop.ShopCategories',
        'refid' => $shopCategory->id
    ], ['class' => 'ui button', 'data-icon' => 'plus']); ?>


    <?php $this->Tabs->add('Link existing module'); ?>
    <h3>Link existing module</h3>
    <div class="ui form">
        <?= $this->Form->create(null, ['url' => ['action' => 'linkModule', $shopCategory->id]]); ?>
        <?= $this->Form->input('refscope', ['default' => 'Shop.ShopCategories']); ?>
        <?= $this->Form->input('refid', ['default' => $shopCategory->id]); ?>
        <?= $this->Form->input('module_id', ['options' => $availableModules]); ?>
        <?= $this->Form->input('section', ['options' => $contentSections]); ?>
        <?= $this->Form->submit('Link module'); ?>
        <?= $this->Form->end(); ?>
    </div>

    <?php echo $this->Tabs->render(); ?>










    <!-- LEGACY CODE --->



    <div class="ui divider"></div>
    <h2>Legacy</h2>



    <?php $this->Tabs->start(); ?>
    <?php $this->Tabs->add(__d('shop', 'Texts')); ?>


    <?php
    // short desc
    $_shortDesc = $shopCategory->getDescShort($locale);
    $_url = [
        'controller' => 'ShopTexts',
        'action' => 'edit_iframe',
        'model' => 'Shop.ShopCategories',
        'model_id' => $shopCategory->id,
        'model_scope' => 'desc_short_text',
        //'format' => 'html',
        'locale' => $locale
    ];
    ?>
    <h5>
        Short Description
        <a href="#" class="be-btn-shoptext-modal" data-modal-url="<?= Router::url($_url); ?>">Popup Editor</a>
    </h5>
    <div class="editor">
        <?= $this->element('Shop.ShopTexts/form', ['shopText' => $_shortDesc]); ?>
    </div>

    <div class="ui divider"></div>

    <?php
    // long desc
    $_longDesc = $shopCategory->getDescLong($locale);
    $_url = [
        'controller' => 'ShopTexts',
        'action' => 'edit_iframe',
        'model' => 'Shop.ShopCategories',
        'model_id' => $shopCategory->id,
        'model_scope' => 'desc_long_text',
        //'format' => 'html',
        'locale' => $locale
    ];
    ?>
    <h5>
        Long Description
        <a href="#" class="be-btn-shoptext-modal" data-modal-url="<?= Router::url($_url); ?>">Popup Editor</a>
    </h5>
    <div class="text">
        <?= '' // ($_longDesc) ? h($_longDesc->text) : __d('shop', 'No text added yet') ?>
    </div>
    <div class="editor">
        <?= $this->element('Shop.ShopTexts/form', ['shopText' => $_longDesc]); ?>
    </div>

    <?php $this->append('script-bottom'); ?>
    <script>
        $('.be-btn-shoptext-modal').click(function() {
            console.log("clicked modal with url " + $(this).data('modalUrl'));
            $modal = $('<div>', {
                "class": "ui modal"
            });
            $iframe = $('<iframe>', {
                src: $(this).data('modalUrl'),
                width: '100%',
                height: '600px'
            }).appendTo($modal);

            $modal.modal('setting', {
                "onShow": function() { console.log("show") },
                "onHide": function() {
                    location.reload();
                }
            }).modal('show');
        });
    </script>
    <?php $this->end(); ?>





    <?= $this->Tabs->render(); ?>

    <?php debug($shopCategory->tags); ?>
    <?php debug($shopCategory); ?>
</div>