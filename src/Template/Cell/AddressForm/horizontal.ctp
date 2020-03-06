<div class="form">
    <?= $this->Form->create($address, ['horizontal' => true, 'novalidate' => true]); ?>

    <?= $this->Form->control('company_name', ['label' => __d('shop','Company Name')]); ?>
    <?= $this->Form->control('taxid', ['label' => __d('shop','Tax Id')]); ?>
    <?= $this->Form->control('first_name', ['label' => __d('shop','First Name')]); ?>
    <?= $this->Form->control('last_name', ['label' => __d('shop','Last Name')]); ?>
    <?= $this->Form->control('street', ['label' => __d('shop','Street')]); ?>
    <?= $this->Form->control('zipcode', ['label' => __d('shop','Zipcode')]); ?>
    <?= $this->Form->control('city', ['label' => __d('shop', 'City')]); ?>
    <?= $this->Form->control('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>

    <div class="actions" style="margin-top: 1em;">
        <?= $this->Form->submit(__d('shop','Save'), ['class' => 'btn btn-lg btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>