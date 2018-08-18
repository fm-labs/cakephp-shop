<?php
use Cake\Utility\Inflector;
?>
<?= __d('shop','{0} ending with {1}', Inflector::humanize($order->cc_brand), substr($order->cc_number, -4)); ?><br />
<?= $order->cc_holder_name; ?><br />
<?= __d('shop','Valid until {0}', $order->cc_expires_at); ?><br />
