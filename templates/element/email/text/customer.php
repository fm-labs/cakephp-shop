<?php
if (!isset($customer)) return false;
?>
Vorname: <?= $customer['first_name'] ?>

Zuname: <?= $customer['last_name'] ?>

Telefon: <?= $customer['phone'] ?>

Fax: <?= $customer['fax'] ?>

Email: <?= $customer['email'] ?>