<div class="cell shop products-list">
    <?php foreach($products as $product): ?>
    <div class="product">
        <h1>[<?= h($product->id); ?>] <?= $this->Html->link($product->title, [
                'controller' => 'ShopProducts',
                'action' => 'view',
                $product->id
            ]); ?></h1>
    </div>
    <?php endforeach; ?>

    <?php debug($products); ?>
</div>