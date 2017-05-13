<div class="shop payment element-pay element" style="text-align: center;">

    <p>
        Sie werden nun zu unserem Zahlungspartner mPAY24 weitergeleitet ...
    </p>


    <?= $this->Html->link(__('Continue to secure payment page'), ['action' => 'pay', $order->uuid], ['class' => 'btn btn-lg btn-primary']); ?>
</div>