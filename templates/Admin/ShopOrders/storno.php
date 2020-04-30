<div class="view">
    <h2><?= __d('shop', 'Do you really want to storno order {0}?', $order->nr_formatted); ?></h2>
    <?= $this->Form->postLink(__d('shop', 'Storno order'), [], ['class' => 'btn btn-primary']); ?>
    <hr />
    <?php echo $this->cell('Admin.EntityView', [$order], [
        'model' => 'Shop.ShopOrders',
        'whitelist' => ['nr_formatted', 'created', 'customer_email']
    ]);?>

</div>