
<div class="shop customer view container">

    <div class="row">
        <div class="col-md-6">

            <h2><?= '' // h($customer->display_name); ?></h2>
            <?= $this->cell('Backend.EntityView', [ $customer ], [
                'title' => false,
                'model' => 'Shop.ShopCustomers',
                'fields' => ['email', 'greeting', 'first_name', 'last_name'],
                'exclude' => '*'
            ]); ?>


            <?= $this->Html->link(__d('shop', 'My orders'), ['controller' => 'Orders', 'action' => 'index'], ['class' => 'btn btn-primary']); ?>
        </div>
        <div class="col-md-6">

        </div>
    </div>



</div>