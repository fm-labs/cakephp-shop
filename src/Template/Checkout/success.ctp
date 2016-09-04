<div class="shop checkout success compact container">
    <h1><?= __d('shop', 'Your order was successful!'); ?></h1>


    <div class="ui actions" style="text-align: left;">
        <?= '' // $this->Html->link(__d('shop','View Order'), ['controller' => 'ShopOrders', 'action' => 'view', $orderKey], ['class' => 'ui primary button']); ?>
        <?= $this->Html->link(__d('shop','Continue'), ['controller' => 'Catalogue', 'action' => 'index'], ['class' => 'ui primary button']); ?>
    </div>
</div>