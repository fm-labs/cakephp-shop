<?php
?>
<div>
    <h4>Cart</h4>
    <ul>
        <li><?= $this->Html->link('Show cart', ['plugin' => 'Shop', 'controller' => 'Cart', 'action' => 'index', 'prefix' => null]); ?></li>
        <li><?= $this->Html->link('Show all carts', ['plugin' => 'Shop', 'controller' => 'Carts', 'action' => 'index', 'prefix' => null]); ?></li>
    </ul>


    <h4>Checkout</h4>
    <ul>
        <li><?= $this->Html->link('Show checkout', ['plugin' => 'Shop', 'controller' => 'Checkout', 'action' => 'index', 'prefix' => null]); ?></li>
    </ul>

</div>
