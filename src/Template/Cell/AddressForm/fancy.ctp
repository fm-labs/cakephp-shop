<div class="form">
    <?= $this->Form->create($address, ['horizontal' => true, 'novalidate' => true]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->control('company_name', ['label' => __d('shop','Company Name')]); ?>
        </div>
        <div class="col-md-12">
            <?= $this->Form->control('taxid', ['label' => __d('shop','Tax Id')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $this->Form->control('first_name', ['label' => __d('shop','First Name')]); ?>
        </div>
        <div class="col-md-6">
            <?= $this->Form->control('last_name', ['label' => __d('shop','Last Name')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->control('street', ['label' => __d('shop','Street')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $this->Form->control('zipcode', ['label' => __d('shop','Zipcode')]); ?>
        </div>
        <div class="col-md-9">
            <?= $this->Form->control('city', ['label' => __d('shop', 'City')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->control('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>
        </div>
    </div>
    <div class="actions" style="margin-top: 1em;">
        <?= $this->Form->submit(__d('shop','Save'), ['class' => 'btn btn-lg btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>