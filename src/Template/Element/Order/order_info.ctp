<?php
$this->loadHelper('Time');
$this->loadHelper('Banana.Status');
?>
<div class="shop order element-info element">
    <dl class="dl-horizontal">
        <dt><?= __d('shop','Order reference'); ?></dt>
        <dd><?= h($order->nr_formatted); ?></dd>
        <dt><?= __d('shop','Date of purchase'); ?></dt>
        <dd><?= h($this->Time->nice($order->submitted)); ?></dd>
        <dt><?= __d('shop','Status'); ?></dt>
        <dd><?= $this->Status->label($order->status__status); ?></dd>
    </dl>
</div>