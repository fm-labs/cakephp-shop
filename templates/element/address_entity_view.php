<?php if (!isset($address)) {
    echo (isset($empty)) ? $empty : "";
    return;
}
?>
<div class="address">
    <?= $this->cell('Backend.EntityView', [ $address ], [
        'title' => false,
        'model' => 'Shop.ShopOrderAddresses',
        'whitelist' => ['company_name', 'first_name', 'last_name', 'street', 'street2', 'zipcode', 'city', 'country_name'],
        'fields' => [
            'shipping_type' => [],
        ],
    ])->render(); ?>
</div>