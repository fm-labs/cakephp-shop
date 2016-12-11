<div class="shop checkout newcustomer form" style="width: 70%; max-width: 800px; margin: 0 auto;">
    <h1><?= __d('shop','Order without registration'); ?></h1>
    <?= $this->Html->link(__d('shop','Already registered? Sign in here'), ['action' => 'customer'], ['class' => '']); ?>

    <div class="form">
        <?= $this->Form->create($newCustomer); ?>

        <?= $this->Form->input('first_name', ['required' => true]); ?>
        <?= $this->Form->input('last_name', ['required' => true]); ?>
        <?= $this->Form->input('email'); ?>
        <?php if ($this->Form->error('email')): ?>
        <div class="alert alert-danger">
            <h2><?= __d('shop','This email is already registered'); ?></h2>
            <br />
            <?= $this->Html->link(__d('shop','Forgot your password?'),
                ['plugin' => 'User', 'controller' => 'User', 'action' => 'password_reset', 'e' => base64_encode($this->Form->value('email'))]); ?>
        </div>
        <?php endif; ?>

        <div class="actions" style="margin-top: 1em;">
            <div class="pull-right">
                <?= $this->Form->button(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        <?= $this->Form->end(); ?>
    </div>

</div>