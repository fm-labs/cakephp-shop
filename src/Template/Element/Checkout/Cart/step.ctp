<li class="<?= $class ?>">
    <h4 class="list-group-item-heading">
        <span class="icon" style="width: 25px; display: inline-block; text-align: right; padding-right: 5px;">
        <i class="fa fa-<?= $step['icon']; ?>"></i>
        </span>
        <?= $this->Html->link($step['title'], ['action' => $method]); ?>
    </h4>
    <?php if ($method != 'cart' && $method != 'review'): ?>
    <p class="list-group-item-text">
        <?= $this->element('Shop.Checkout/cart'); ?>
    </p>
    <?php endif; ?>
</li>