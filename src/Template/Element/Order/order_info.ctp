<div class="shop order element-info element">
    <dl class="dl-horizontal">
        <dt><?= __('Order reference'); ?></dt>
        <dd><?= h($order->nr_formatted); ?></dd>
        <dt><?= __('Date of purchase'); ?></dt>
        <dd><?= h($this->Time->nice($order->submitted)); ?></dd>
    </dl>
</div>