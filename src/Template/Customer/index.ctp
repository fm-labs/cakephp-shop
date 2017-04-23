<?php $this->Breadcrumbs->add(__d('shop','Your account'), ['controller' => 'Customer', 'action' => 'index']); ?>

<div class="shop customer view container">

    <h1>Welcome, <?= $this->request->session()->read('Auth.User.displayName'); ?></h1>

    <div class="row">
        <div class="col-md-12">
            <h2>
                <i class="fa fa-list"></i>
                <?= __d('shop','Orders'); ?>
            </h2>


            <p>Unser Portfolio enthält zur Zeit 64 Produkte, die sie über unseren Webshop beziehen können.</p>

            <?= $this->Html->link(__d('shop', 'List orders'), ['controller' => 'Orders', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
            <?= $this->Html->link(__d('shop', 'New order'), ['controller' => 'Orders', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>

            <hr />
        </div>


        <div class="col-md-12">
            <h2>
                <i class="fa fa-user-circle"></i>
                <?= __d('shop','My profile'); ?>
            </h2>

            <p>Einstellung zu Ihrem persönlichen Profil.</p>

            <?= $this->Html->link(__d('shop', 'Edit billing addresses'),
                ['plugin' => 'Shop', 'controller' => 'BillingAddresses', 'action' => 'index'],
                ['class' => 'btn btn-primary']); ?>
            <?= $this->Html->link(__d('shop', 'Edit profile'),
                ['_name' => 'user:profile'],
                ['class' => 'btn btn-primary']); ?>
            <?= $this->Html->link(__d('shop', 'Change password'),
                ['_name' => 'user:passwordchange'],
                ['class' => 'btn btn-primary']); ?>
            <?= $this->Html->link(__d('shop', 'Sign out'),
                ['_name' => 'user:logout'],
                ['class' => 'btn btn-primary']); ?>

            <hr />
        </div>
    </div>

</div>