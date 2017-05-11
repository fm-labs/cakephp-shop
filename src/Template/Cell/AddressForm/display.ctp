<div class="form">
    <?= $this->Form->create($address, ['horizontal' => true, 'novalidate' => true]); ?>

    <?php if ($options['company']): ?>
    <?= $this->Form->input('company_name', ['label' => __d('shop','Company Name')]); ?>
    <?php endif; ?>
    <?= $this->Form->input('first_name', ['label' => __d('shop','First Name')]); ?>
    <?= $this->Form->input('last_name', ['label' => __d('shop','Last Name')]); ?>
    <?= $this->Form->input('street', ['label' => __d('shop','Street')]); ?>
    <?= $this->Form->input('zipcode', ['label' => __d('shop','Zipcode')]); ?>
    <?= $this->Form->input('city', ['label' => __d('shop', 'City')]); ?>
    <?= $this->Form->input('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>
    <?php if ($options['taxid']): ?>
        <?= $this->Form->input('taxid', ['label' => __d('shop','Tax Id')]); ?>
    <?php endif; ?>

    <div class="actions text-right">
        <?= $this->Form->submit($options['submit'], ['class' => 'btn btn-lg btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>