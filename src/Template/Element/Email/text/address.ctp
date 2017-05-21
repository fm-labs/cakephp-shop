<?php
if (!isset($address)) return false;
?>
Vorname: <?= $address['first_name'] ?>

Zuname: <?= $address['last_name'] ?>

Adresse: <?= $address['street'] ?>

Adresse2: <?= $address['street2'] ?>

PLZ: <?= $address['zipcode'] ?>

Ort: <?= $address['city'] ?>

Land: <?= ($address['relcountry']) ? $address['relcountry']['name_de'] : "" ?>