<div class="form">
    <?= $this->Form->create($address, ['horizontal' => true, 'novalidate' => true]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->input('company_name', ['label' => __d('shop','Company Name')]); ?>
        </div>
        <div class="col-md-12">
            <?= $this->Form->input('taxid', ['label' => __d('shop','Tax Id')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $this->Form->input('first_name', ['label' => __d('shop','First Name')]); ?>
        </div>
        <div class="col-md-6">
            <?= $this->Form->input('last_name', ['label' => __d('shop','Last Name')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->input('street', ['label' => __d('shop','Street')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $this->Form->input('zipcode', ['label' => __d('shop','Zipcode')]); ?>
        </div>
        <div class="col-md-9">
            <?= $this->Form->input('city', ['label' => __d('shop', 'City')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->input('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>
        </div>
    </div>
    <div class="actions" style="margin-top: 1em;">
        <?= $this->Form->submit(__d('shop','Save'), ['class' => 'btn btn-lg btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>