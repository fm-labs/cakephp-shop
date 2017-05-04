<?php if (!isset($address) || !$address) {
    echo __d('shop', "No address set");
    return;
}
?>
<div class="address">
    <?php if ($address->company_name): ?>
        <?= h($address->company_name); ?>
        <br />
    <?php endif; ?>
    <?= h($address->first_name); ?>
    <?= h($address->last_name); ?><br />
    <?= h($address->street); ?><br />
    <?= h($address->zipcode); ?>
    <?= h($address->city); ?><br />
    <?= h($address->relcountry->name_de); ?><br />

    <?php if (isset($edit) && $edit === true): ?>
        <?= $this->Ui->button(__('Edit'), ['action' => 'edit', $address->id], ['class' => 'btn-link']); ?>
    <?php endif; ?>
</div>