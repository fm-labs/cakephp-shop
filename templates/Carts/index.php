<?php
$currentCart = $this->get('cart');
?>
<div class="container">
    <h1>Stale carts</h1>

    <?php foreach($this->get('carts') as $_cart): ?>
    <div class="d-flex flex-column">
        <span class="<?= $currentCart && $currentCart->cartid == $_cart['cartid'] ? "fw-bold": "fst-italic" ?>">
            <?php echo $this->Html->link($_cart['cartid'], ['action' => 'restore', $_cart['cartid']]); ?>
        </span>
        <span>UUID: <?php echo h($_cart['uuid']); ?></span>
        <span>SessionID: <?php echo h($_cart['sessionid']); ?></span>
        <span>Created: <?php echo h($_cart['created']); ?></span>
        <span><?= __('{0} items', count($_cart['shop_order_items'] ?? [])); ?></span>
        <hr />
    </div>
    <?php endforeach; ?>
</div>
