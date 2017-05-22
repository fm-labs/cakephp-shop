<?php
$paymentMap = ['payment_slip' => 'Rechnung mit Erlagschein', 'credit_card_internal' => 'Kreditkarte'];
?>

    Zahlungsart: <?= (isset($paymentMap[$order->payment_type])) ?  $paymentMap[$order->payment_type] : $order->payment_type; ?>

<?php if ($order->payment_type == 'credit_card_internal'): ?>
    <?php $type = explode(':', $order->payment_info_1); ?>
    Kreditkartentyp: <?= ucfirst($type[0]); ?>

    Kreditkartennummer: <?= $type[1] ?>

    Karteninhaber: <?= $order->payment_info_2 ?>

    Ablaufdatum: <?= $order->payment_info_3 ?>

<?php endif; ?>