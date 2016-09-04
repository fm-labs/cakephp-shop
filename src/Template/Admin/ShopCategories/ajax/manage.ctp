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


    <div class="panel panel-primary">
        <div class="panel-heading">
                <?= __d('shop', '{0}: {2} [{1}]', __d('shop', 'Shop Category'), (string) $locale, $shopCategory->name) ?>

        </div>
        <div class="panel-body">


            <div class="row">
                <div class="col-md-8">

                    <p>
                        Published: <?= $this->Ui->statusLabel($shopCategory->is_published); ?><br />
                        Url: <?= $this->Html->link($shopCategory->url); ?><br />
                        PermaUrl: <?= $this->Html->link($shopCategory->permaUrl); ?>
                        <br />

                    </p>
                </div>
                <div class="col-md-4">
                    <div class="actions right grouped">
                        <ul>
                            <li><?= $this->Ui->link(
                                    __d('shop','Edit'),
                                    ['action' => 'edit', $shopCategory->id],
                                    ['class' => 'link-frame-modal btn btn-default btn-sm btn-primary', 'icon' => 'edit']);
                                ?>
                            </li>
                            <li><?= $this->Html->link(__d('shop','Preview'),
                                    [ 'action' => 'preview', $shopCategory->id ],
                                    [ 'class' => 'preview link-frame-modal btn btn-default btn-sm', 'data-icon' => 'eye', 'target' => '_preview']);
                                ?>
                            </li>
                            <li><?= $this->Html->link(__d('shop','Open'),
                                    $shopCategory->url,
                                    [ 'class' => 'link-external btn btn-default btn-sm', 'data-icon' => 'external-link', 'target' => '_blank']);
                                ?>
                            </li>
                            <li><?= $this->Html->link(__d('shop','Delete'),
                                    [ 'action' => 'delete', $shopCategory->id ],
                                    [ 'class' => 'delete btn btn-danger btn-sm',
                                        'data-icon' => 'trash-o',
                                        'confirm' => __d('shop','Sure ?'),
                                    ]);
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr />
            <?php $this->Tabs->start(); ?>

            <?php $this->Tabs->add(__d('shop','Shop Category'), [
                'url' => ['action' => 'view', $shopCategory->id]
            ]); ?>

            <?php
            //$this->Tabs->add(__d('shop','Edit'), [
            //    'url' => ['action' => 'edit', $shopCategory->id]
            //]);
            ?>

            <!-- RELATED PRODUCTS -->
            <?php $this->Tabs->add(__d('shop', 'Products'), [
                'url' => ['action' => 'relatedProducts', $shopCategory->id]
            ]); ?>

            <?php $this->Tabs->add('Meta', [
                'url' => ['action' => 'relatedPageMeta', $shopCategory->id]
            ]); ?>

            <?php $this->Tabs->add('Content Modules', [
                'url' => ['action' => 'relatedContentModules', $shopCategory->id]
            ]); ?>

            <?php echo $this->Tabs->render(); ?>

        </div>
    </div>

    <?php debug($shopCategory); ?>
</div>