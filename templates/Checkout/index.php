<div class="shop checkout container">
    <h1> Checkout Index Page </h1>

    <?php debug($order); ?>

    <br />
    <?php echo $this->Html->link('Next', ['action' => 'next', $order->cartid]); ?>
</div>
