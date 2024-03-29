<?php if (!isset($address)) {
    echo (isset($empty)) ? $empty : "";
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
    <?php if ($address->taxid): ?>
    <?= h($address->taxid); ?><br />
    <?php endif; ?>

    <?php if (isset($edit) && $edit === true): ?>
        <?= $this->Ui->button(__d('shop','Edit'), ['action' => 'edit', $address->id], ['class' => 'btn-link']); ?>
    <?php endif; ?>

    <?php
    // @TODO Google Maps Link
    // @TODO Export to vcard
    // @TODO Semantic markup
    ?>
</div>