<?php $this->Breadcrumbs->add(__d('shop', 'Shop Countries'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($shopCountry->name); ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Edit {0}', __d('shop', 'Shop Country')),
    ['action' => 'edit', $shopCountry->id],
    ['data-icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'Delete {0}', __d('shop', 'Shop Country')),
    ['action' => 'delete', $shopCountry->id],
    ['data-icon' => 'trash', 'confirm' => __d('shop', 'Are you sure you want to delete # {0}?', $shopCountry->id)]) ?>

<?= $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Countries')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __d('shop', 'New {0}', __d('shop', 'Shop Country')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?= $this->Toolbar->startGroup(__d('shop', 'More')); ?>
<?= $this->Toolbar->endGroup(); ?>
<div class="shopCountries view">
    <h2 class="ui header">
        <?= h($shopCountry->name) ?>
    </h2>

    <?php
    echo $this->cell('Backend.EntityView', [ $shopCountry ], [
        'title' => $shopCountry->title,
        'model' => 'Shop.ShopCountries',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">


        <tr>
            <td><?= __d('shop', 'Iso2') ?></td>
            <td><?= h($shopCountry->iso2) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Iso3') ?></td>
            <td><?= h($shopCountry->iso3) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Name') ?></td>
            <td><?= h($shopCountry->name) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Name De') ?></td>
            <td><?= h($shopCountry->name_de) ?></td>
        </tr>


        <tr>
            <td><?= __d('shop', 'Id') ?></td>
            <td><?= $this->Number->format($shopCountry->id) ?></td>
        </tr>
        <tr>
            <td><?= __d('shop', 'Priority') ?></td>
            <td><?= $this->Number->format($shopCountry->priority) ?></td>
        </tr>

        <tr class="boolean">
            <td><?= __d('shop', 'Is Published') ?></td>
            <td><?= $shopCountry->is_published ? __d('shop', 'Yes') : __d('shop', 'No'); ?></td>
        </tr>
    </table>
</div>
-->



