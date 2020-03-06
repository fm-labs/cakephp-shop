<?php $this->Breadcrumbs->add(__d('shop', 'Shop Countries'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('shop', 'New {0}', __d('shop', 'Shop Country'))); ?>
<?php $this->Toolbar->addLink(
    __d('shop', 'List {0}', __d('shop', 'Shop Countries')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('shop', 'Add {0}', __d('shop', 'Shop Country')) ?>
    </h2>
    <?= $this->Form->create($shopCountry, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
                echo $this->Form->control('iso2');
                echo $this->Form->control('iso3');
                echo $this->Form->control('name');
                echo $this->Form->control('name_de');
                echo $this->Form->control('priority');
                echo $this->Form->control('is_published');
        ?>
        </div>

    <?= $this->Form->button(__d('shop', 'Submit')) ?>
    <?= $this->Form->end() ?>

</div>