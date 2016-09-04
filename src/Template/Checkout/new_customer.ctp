<div class="shop checkout newcustomer form">
    <h1><?= __d('shop','New Customer'); ?></h1>
    <?= $this->Html->link('Bereits registriert? Hier anmelden', ['action' => 'customer'], ['class' => '']); ?>
    <div class="ui divider"></div>

    <div class="ui form">
        <div class="ui form">
            <?= $this->Form->create($newCustomer); ?>
            <?= $this->Form->input('email'); ?>

            <div class="ui divider"></div>
            <?= $this->Form->input('is_guest', [
                'id' => 'toggle-guest',
                'label' => __d('shop','Continue as guest'),
            ]); ?>
            <div id="pw-container">
                <?= $this->Form->input('password1', ['type' => 'password', 'label' => __d('shop','Password')]); ?>
                <?= $this->Form->input('password2', ['type' => 'password', 'label' => __d('shop','Password')]); ?>
            </div>
            <div class="actions" style="margin-top: 1em;">
                <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'ui primary submit button']); ?>
            </div>
            <?= $this->Form->end(); ?>
        </div>
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