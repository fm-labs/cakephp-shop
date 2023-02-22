<div class="form">
    <?= $this->Form->create($address, ['horizontal' => true, 'novalidate' => true]); ?>

    <?php if ($options['company']): ?>
    <?= $this->Form->control('company_name', ['label' => __d('shop','Company Name')]); ?>
    <?php endif; ?>
    <?= $this->Form->control('first_name', ['label' => __d('shop','First Name')]); ?>
    <?= $this->Form->control('last_name', ['label' => __d('shop','Last Name')]); ?>
    <?= $this->Form->control('street', ['label' => __d('shop','Street')]); ?>
    <?= $this->Form->control('zipcode', ['label' => __d('shop','Zipcode')]); ?>
    <?= $this->Form->control('city', ['label' => __d('shop', 'City')]); ?>
    <?= $this->Form->control('country_id', ['label' => __d('shop','Country'), 'options' => $this->get('countries')]); ?>
    <?php if ($options['taxid']): ?>
        <?= $this->Form->control('taxid', ['label' => __d('shop','Tax Id')]); ?>
    <?php endif; ?>

    <div class="actions text-end">
        <?= $this->Form->submit($options['submit'], ['class' => 'btn btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>