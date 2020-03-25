<?php $this->loadHelper('Backend.Box'); ?>
<?php $this->loadHelper('Bootstrap.Ui'); ?>
<?php $this->assign('title', __('Shop Settings')); ?>
<?php /** @var \Settings\Form\SettingsForm $form */ ?>
<div class="index">
    <?php
    $this->Form->addContextProvider('settings_form', function($request, $context) {
        if ($context['entity'] instanceof \Settings\Form\SettingsForm) {
            return new \Settings\View\Form\SettingsFormContext($request, $context);
        }
    });
    ?>
    <?= $this->Form->create($form, ['horizontal' => true]); ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-8">
            <?php $this->Box->create(__('Owner')); ?>
            <?= $this->Form->controls($form->getInputs([
                'Shop.Owner.name',
                'Shop.Owner.street1',
                'Shop.Owner.street2',
                'Shop.Owner.zipcode',
                'Shop.Owner.city',
                'Shop.Owner.taxId'
            ]), ['fieldset' => false]); ?>
            <?= $this->Box->render(); ?>

            <?= $this->Box->create(__('Numbering')); ?>
            <?= $this->Form->controls([
                'Shop.Order.nrPrefix',
                'Shop.Invoice.nrPrefix',
            ], ['fieldset' => false]); ?>
            <?= $this->Box->render(); ?>

            <?= $this->Box->create(__('Pricing')); ?>
            <?= $this->Form->controls($form->getInputs([
                'Shop.Price.baseCurrency',
                'Shop.Price.displayNet',
                'Shop.Price.requireAuth'
            ]), ['fieldset' => false]); ?>
            <?= $this->Box->render(); ?>

            <?= $this->Box->create(__('Layout')); ?>
            <?= $this->Form->controls([
                'Shop.Layout.default',
                'Shop.Layout.checkout',
                'Shop.Layout.payment',
                'Shop.Layout.order',
            ], ['fieldset' => false]); ?>
            <?= $this->Box->render(); ?>

            <?= $this->Box->create(__('Routing')); ?>
            <?= $this->Form->controls([
                'Shop.Router.enablePrettyUrls',
                'Shop.Router.forceCanonical',
                'Shop.Catalogue.index_category_id'
            ], ['fieldset' => false]); ?>
            <?= $this->Box->render(); ?>
        </div>
    </div>
    <?= $this->Form->submit(); ?>
    <?= $this->Form->end(); ?>
</div>