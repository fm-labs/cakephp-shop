<div class="shop order element-messages element">
    <?php if ($this->request->query('order_complete')): ?>
        <div class="alert alert-success">
            <strong><?= __d('shop','Your order has been submitted'); ?></strong>
            <p><?= __d('shop','A confirmation email has been sent.'); ?></p>
        </div>
    <?php endif; ?>
</div>