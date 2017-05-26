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
        <?= $this->Form->create($user, ['novalidate' => true, 'context' => ['validator' => 'register']]); ?>
        <?= $this->Form->hidden('op', ['value' => 'signup']); ?>

        <?php if ($this->Form->error('email') && isset($user->errors('email')['_isUnique'])): ?>
            <div class="alert alert-danger">
                <h4><?= __d('shop','The email address {0} is already registered', $user->email); ?></h4>
                <br />
                <?= $this->Html->link(__d('shop','Forgot your password?'),
                    ['plugin' => 'User', 'controller' => 'User', 'action' => 'password_reset', 'e' => base64_encode($newCustomer->email)]); ?>
            </div>
        <?php endif; ?>

        <?= $this->Form->input('first_name'); ?>
        <?= $this->Form->input('last_name'); ?>
        <?= $this->Form->input('email'); ?>

        <?= $this->Form->input('password1', ['type' => 'password', 'autocomplete' => 'off', 'label' => __d('shop','Password')]); ?>
        <?= $this->Form->input('password2', ['type' => 'password', 'autocomplete' => 'off', 'label' => __d('shop','Repeat Password')]); ?>

        <div class="actions text-right" style="margin-top: 1em;">
            <?= $this->Form->button(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>

</div>