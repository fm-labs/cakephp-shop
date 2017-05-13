<div class="shop order element-info element">
    <dl class="dl-horizontal">
        <dt><?= __d('shop','Order reference'); ?></dt>
        <dd><?= h($order->nr_formatted); ?></dd>
        <dt><?= __d('shop','Date of purchase'); ?></dt>
        <dd><?= h($this->Time->nice($order->submitted)); ?></dd>
    </dl>
</div>