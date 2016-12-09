<?php $this->Breadcrumbs->add(__d('shop', 'Shop Texts'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopText->id); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Edit {0}', __d('shop', 'Shop Text')),
    ['action' => 'edit', $shopText->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Delete {0}', __d('shop', 'Shop Text')),
    ['action' => 'delete', $shopText->id],
    ['data-icon' => 'remove', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopText->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Texts')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Text')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop', 'More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="shopTexts view">
    <h2 class="ui header">
        <?= h($shopText->id) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __d('shop', 'Label'); ?></th>
            <th><?= __d('shop', 'Value'); ?></th>
        </tr>
        </thead>
        -->

        <tr>
            <td><?= __d('shop', 'Model') ?></td>
            <td><?= h($shopText->model) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Model Scope') ?></td>
            <td><?= h($shopText->model_scope) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Lang') ?></td>
            <td><?= h($shopText->locale) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Format') ?></td>
            <td><?= h($shopText->format) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Class') ?></td>
            <td><?= h($shopText->class) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop', 'Id') ?></td>
            <td><?= $this->Number->format($shopText->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Model Id') ?></td>
            <td><?= $this->Number->format($shopText->model_id) ?></td>
        </tr>

        <tr class="text">
            <td><?= __d('shop', 'Text') ?></td>
            <td><?= $this->Text->autoParagraph(h($shopText->text)); ?></td>
        </tr>
    </table>
</div>
