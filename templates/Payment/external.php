<?php
$external = $this->get('external');

$this->Breadcrumbs->add(__d('shop','Shop'), ['_name' => 'shop:index', 'ref' => 'breadcrumb']);
$this->Breadcrumbs->add(__d('shop','Payment via secure payment partner'));
//$this->Breadcrumbs->add(__d('shop','via secure payment partner'), $external['url']);

//$this->disableAutoLayout();
?>
<div>
    <?php echo $this->Html->tag('iframe', '', [
        'src' => $external['url'],
        'class' => 'shop-payment-iframe',
        'style' => 'width: 100%; height: calc(100vh - 100px);'
    ]); ?>
    <hr />
    <p class="text-muted text-center">
        Payment page not showing or problems with the payment?
        <?php echo $this->Html->link(__d('shop', 'Click here'), $external['url']); ?>
    </p>
</div>



