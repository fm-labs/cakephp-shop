<?php
/**
 * @deprecated
 * This template is deprecated.
 * Use customer_signup or customer_guest template instead.
 *
 */
?>
<div class="shop checkout newcustomer form">
    <h1><?= __d('shop','New Customer'); ?></h1>
    <?= $this->Html->link('Bereits registriert? Hier anmelden', ['action' => 'customer'], ['class' => '']); ?>
    <div class="ui divider"></div>

    <div class="form">
        <?= $this->Form->create($newCustomer); ?>
        <?= $this->Form->input('email'); ?>
        <?php if ($this->Form->error('email')): ?>
        <div class="alert alert-danger">
            <h2><?= __d('shop','This email is already registered'); ?></h2>
            <br />
            <?= $this->Html->link(__d('shop','Forgot your password?'), ['plugin' => 'User', 'controller' => 'User', 'action' => 'password_reset', 'e' => base64_encode($this->Form->value('email'))]); ?>
        </div>
        <?php endif; ?>

        <div class="ui divider"></div>
        <?= $this->Form->input('is_guest', [
            'type' => 'checkbox',
            'id' => 'toggle-guest',
            'label' => __d('shop','Continue as guest'),
        ]); ?>
        <div id="pw-container">
            <?= $this->Form->input('password1', ['type' => 'password', 'autocomplete' => 'nope', 'label' => __d('shop','Password')]); ?>
            <?= $this->Form->input('password2', ['type' => 'password', 'autocomplete' => 'nope', 'label' => __d('shop','Repeat Password')]); ?>
        </div>
        <div class="actions" style="margin-top: 1em;">
            <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'btn btn-primary']); ?>
        </div>
        <?= $this->Form->end(); ?>
    </div>

</div>
<script>
    $(document).ready(function () {
        var $toggle = $('#toggle-guest');
        var $cont = $('#pw-container');

        $toggle.on('change', function() {
            var checked = $(this).is(':checked');
            if (checked) {
                $cont.slideUp();
            } else {
                $cont.slideDown();
            }
        }).trigger('change');
    });
</script>