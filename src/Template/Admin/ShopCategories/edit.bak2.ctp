<?php
use Backend\View\Widget\ImageSelectWidget;
use Cake\Core\Configure;
use Cake\Routing\Router;
?>
<?php $this->loadHelper('Backend.Tabs'); ?>
<?php $this->Html->addCrumb(__d('shop', 'Shop Categories'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__d('shop', 'Edit {0}', __d('shop', 'Shop Category'))); ?>
<?= $this->Toolbar->addPostLink(
    __d('shop', 'Delete'),
    ['action' => 'delete', $shopCategory->id],
    ['icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCategory->id)]
)
?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Categories')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Add {0}', __d('shop', 'Shop Category')),
    ['action' => 'add', 'parent_id' => $shopCategory->parent_id],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Add {0}', __d('shop', 'Sub Category')),
    ['action' => 'add', 'parent_id' => $shopCategory->id],
    ['icon' => 'plus']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Products')),
    ['controller' => 'ShopProducts', 'action' => 'index'],
    ['icon' => 'list']
) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Product')),
    ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
    ['icon' => 'plus']
) ?>
<?php $this->Toolbar->endGroup(); ?>
<?php $this->assign('title', '[ShopCategory] ' . $shopCategory->name); ?>
<div class="shop categories form">

    <div class="ui basic message locales">
        <?php $_locales = Configure::read('Shop.locales'); ?>
        <strong><?= __d('shop', '[{0} version]', $_locales[$locale]); ?></strong> |
        <?php foreach($_locales as $_locale => $_localeName): ?>
            <?= $this->Html->link(__d('shop', 'Edit {0} version', $_localeName), ['action' => 'edit', $shopCategory->id, 'locale' => $_locale]) ?> |
        <?php endforeach; ?>
    </div>

    <div class="ui fluid card">
        <div class="content">
            <div class="header">
                <i class="edit"></i>
                <?= __d('shop', 'Edit {0}: {2} [{1}]', __d('shop', 'Shop Category'), (string) $locale, $shopCategory->name) ?>
            </div>
        </div>
        <div class="content">
            Published: <?= $this->Ui->statusLabel($shopCategory->is_published); ?>
        </div>
        <div class="extra">
            Url: <?= $this->Html->link($shopCategory->url); ?><br />
            <small>PermaUrl: <?= $this->Html->link($shopCategory->permaUrl); ?></small>
        </div>
    </div>


    <!--
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
    -->

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
                        'editor' => '@Shop.HtmlEditor.default'
                    ]);
                    echo $this->Form->input('desc_html', [
                        'type' => 'htmleditor',
                        'editor' => '@Shop.HtmlEditor.default'
                    ]);
                    //echo $this->Form->input('is_published');
                    echo $this->Form->input('tags._ids', ['multiple' => 'checkbox']);
                    ?>

                </div>


            </div>
            <div class="four wide column">

                <div class="ui attached basic right aligned segment form">
                    <?= $this->Form->button(__d('shop', 'Save Changes'), ['class' => 'ui positive fluid button']) ?>
                </div>
                <h5 class="ui attached header">Publish</h5>
                <div class="ui attached segment form">
                    <?php
                    echo $this->Form->input('is_published');
                    //echo $this->Form->input('publish_start_date', ['type' => 'datepicker']);
                    //echo $this->Form->input('publish_end_date', ['type' => 'datepicker']);
                    ?>
                </div>

                <h5 class="ui attached header">Preview Image</h5>
                <div class="ui attached segment form">
                    <?php
                    if ($shopCategory->preview_image_file) {
                        echo $this->Html->image($shopCategory->preview_image_file->url, ['width' => 200]) . '<br />';
                        echo h($shopCategory->preview_image_file->basename) . '<br />';
                    }
                    ?>
                    <?php
                    echo $this->Ui->link(
                        __d('shop','Select Image'),
                        ['action' => 'setImage', $shopCategory->id, 'scope' => 'preview_image_file' ],
                        ['class' => 'link-frame-modal', 'icon' => 'folder open outline']
                    );
                    ?>
                    <?php
                    echo $this->Ui->link(
                        __d('shop','Remove Image'),
                        ['action' => 'deleteImage', $shopCategory->id, 'scope' => 'preview_image_file' ],
                        ['icon' => 'remove circle']
                    );
                    ?>
                </div>

                <h5 class="ui attached header">Featured Image</h5>
                <div class="ui attached segment form">
                    <?php
                    if ($shopCategory->featured_image_file) {
                        echo $this->Html->image($shopCategory->featured_image_file->url, ['width' => 200]) . '<br />';
                        echo h($shopCategory->featured_image_file->basename) . '<br />';
                    }
                    ?>
                    <?php
                    echo $this->Ui->link(
                        __d('shop','Select Image'),
                        ['action' => 'setImage', $shopCategory->id, 'scope' => 'featured_image_file' ],
                        ['class' => 'link-frame-modal', 'icon' => 'folder open outline']
                    );
                    ?>
                    <?php
                    echo $this->Ui->link(
                        __d('shop','Remove Image'),
                        ['action' => 'deleteImage', $shopCategory->id, 'scope' => 'featured_image_file' ],
                        ['icon' => 'remove circle']
                    );
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

    <!-- Custom Texts -->
    <?php $this->Tabs->add(__d('shop', 'Custom Texts')); ?>

    <div class="form">
        <?= $this->Form->create($shopCategory); ?>
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
        <?= ''//$this->Form->input('custom_text3', ['type' => 'htmleditor']); ?>
        <?= ''//$this->Form->input('custom_text4', ['type' => 'htmleditor']); ?>
        <?= ''//$this->Form->input('custom_text5', ['type' => 'htmleditor']); ?>
        <?= $this->Form->button(__d('shop', 'Submit')) ?>
        <?= $this->Form->end() ?>
    </div>


    <!-- RELATED PRODUCTS -->
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
                        ['class' => 'ui mini button', 'icon' => 'edit']
                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="actions">
        <?= $this->Ui->link('Add Product',
            ['controller' => 'ShopProducts', 'action' => 'add', 'shop_category_id' => $shopCategory->id],
            ['class' => 'ui button', 'icon' => 'plus']
        ); ?>
    </div>


    <?php $this->Tabs->add('Related Content Modules'); ?>
    <!-- RELATED CONTENT MODULES -->
    <h3>Related content modules</h3>
    <?= $this->element('Banana.Admin/Content/related_content_modules', ['content' => $shopCategory]); ?>
    <br />
    <?= $this->Ui->link('Build a new module for this shop category', [
        'controller' => 'ModuleBuilder',
        'action' => 'build2',
        'refscope' => 'Shop.ShopCategories',
        'refid' => $shopCategory->id
    ], ['class' => 'ui button', 'icon' => 'plus']); ?>


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


    <?php debug($shopCategory); ?>
</div>