<?php $this->extend('Shop.Checkout/base'); ?>
<?php $this->assign('heading', __d('shop','Customer Login')); ?>
<?php $this->assign('step_active', 'customer'); ?>
<div class="shop checkout step index" style="position: relative;">
    <div class="row">

        <?php
        /**
         * Customer Login Form
         */
        ?>
        <?php if (!isset($customer->id)): ?>
            <div class="col-xs-12 col-md-6"style="border-right: 1px solid #e8e8e8;">
                <?= $this->Flash->render('auth'); ?>
                <h2><?= __d('shop','Already registered?'); ?></h2>
                <?= $this->Form->create(null, ['url' => ['action' => 'customer', 'login' => true]]); ?>
                <?= $this->Form->input('username', ['required' => true, 'label' => __d('shop','Email')]); ?>
                <?= $this->Form->input('password', ['required' => true]); ?>
                <div class="actions" style="margin-top: 1em;">
                    <?= $this->Form->button(__d('shop','Login'), ['class' => 'btn btn-primary']); ?>
                    <?= $this->Html->link(__d('shop','Forgot password?'), ['_name' => 'user:passwordforgotten']); ?>
                </div>
                <?= $this->Form->end(); ?>
            </div>
            <div class="col-xs-12 col-md-6">

                <h2><?= __d('shop','I\'m a new customer'); ?></h2>
                <div style="text-align: center; margin-top: 4em;">
                    <?= $this->Html->link(__d('shop','Als Neukunde Registrieren'),
                        ['action' => 'customer', 'signup' => true],
                        ['class' => 'btn btn-large btn-primary']); ?>
                </div>

                <div style="text-align: center; margin-top: 2em;">
                <?= $this->Html->link(__d('shop','Weiter ohne Anmeldung'),
                    ['action' => 'customer', 'guest' => true],
                    ['class' => 'btn']); ?>
                </div>
            </div>
            </div>

        <?php
        /**
         * Customer Already logged in as GUEST
         */
        ?>
        <?php elseif ($customer->is_guest): ?>

            <div class="col-md-12">
                <h3>
                    Ohne Anmeldung mit folgender Email-Adresse fortfahren:
                    <br />
                    <?= h($customer->email); ?>
                </h3>

                <div style="text-align: right;">
                    <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'btn btn-primary']); ?>
                </div>
            </div>

        <?php
        /**
         * Customer Already logged in as REGISTERED CUSTOMER
         */
        ?>
        <?php else: ?>

            <div class="col-md-12">
                <h3>
                    Angemeldet als:
                    <br />
                    <?= h($customer->first_name); ?> <?= h($customer->last_name); ?>
                    <br />
                    <?= h($customer->email); ?>
                </h3>

                <p>
                    Sie sind nicht <?= h($customer->email); ?>?
                    <br />
                    <?= $this->Html->link('Als anderer Kunde fortfahren', ['action' => 'resetCustomer']); ?>
                </p>

                <div style="text-align: right;">
                    <?= $this->Html->link(__d('shop','Continue'), ['action' => 'next'], ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php debug($customer); ?>
    <?php debug($this->request->session()->read('Shop')); ?>
</div>