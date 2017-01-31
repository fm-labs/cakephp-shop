<?php if (!isset($address) || !$address) {
    echo __d('shop', "No address set");
    return;
}
?>
<div class="address">
    <?php if ($address->is_company): ?>
        <?= h($address->company_name); ?>
    <?php endif; ?>
    <?= h($address->first_name); ?>
    <?= h($address->last_name); ?><br />
    <?= h($address->street); ?><br />
    <?= h($address->zipcode); ?>
    <?= h($address->city); ?><br />
    <?= h($address->relcountry->name_de); ?>
</div>