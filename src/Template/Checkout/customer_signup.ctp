<?php
$this->Breadcrumbs->add(__d('shop','Shop'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Checkout'), ['controller' => 'Checkout', 'action' => 'index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Customer'), ['controller' => 'Checkout', 'action' => 'customer', 'ref' => 'breadcrumb']);
?>
<div class="shop checkout newcustomer form" style="width: 70%; max-width: 800px; margin: 0 auto;">
    <h1><?= __d('shop','New customer'); ?></h1>
    <?= $this->Html->link(__d('shop','Already registered? Sign in here'), ['action' => 'customer'], ['class' => '']); ?>
    <div class="ui divider"></div>

    <div class="form">
        <?= $this->Form->create($newCustomer, ['novalidate']); ?>

        <?php if ($this->Form->error('email') && isset($newCustomer->errors('email')['_isUnique'])): ?>
            <div class="alert alert-danger">
                <h4><?= __d('shop','The email address {0} is already registered', $newCustomer->email); ?></h4>
                <br />
                <?= $this->Html->link(__d('shop','Forgot your password?'),
                    ['plugin' => 'User', 'controller' => 'User', 'action' => 'password_reset', 'e' => base64_encode($newCustomer->email)]); ?>
            </div>
        <?php endif; ?>

        <?= $this->Form->hidden('is_guest', ['value' => '0']); ?>
        <?= $this->Form->input('first_name', ['required' => true]); ?>
        <?= $this->Form->input('last_name', ['required' => true]); ?>
        <?= $this->Form->input('email', ['required' => true]); ?>

        <?= $this->Form->input('password1', ['type' => 'password', 'autocomplete' => 'nope', 'required' => true, 'label' => __d('shop','Password')]); ?>
        <?= $this->Form->input('password2', ['type' => 'password', 'autocomplete' => 'nope', 'required' => true, 'label' => __d('shop','Repeat Password')]); ?>

        <div class="actions text-right" style="margin-top: 1em;">
            <?= $this->Form->button(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>

</div>