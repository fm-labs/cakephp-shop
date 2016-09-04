<?php $this->loadHelper('Backend.Ui'); ?>
<?php //$this->extend('Shop.Checkout/base'); ?>
<?php //$this->assign('heading', __d('shop','Helllo')); ?>
<div class="shop checkout step index compact container">
    <h1>Ihre Bestellung</h1>
    <table class="ui compact stripped table">
        <thead>
        <tr>
            <th><?= __d('shop','Amount'); ?></th>
            <th><?= __d('shop','Product'); ?></th>
            <th><?= __d('shop','Item Price'); ?></th>
            <th><?= __d('shop','Price'); ?></th>
            <th class="actions"><?= __d('shop','Actions'); ?></th>
        </tr>
        </thead>
        <?php foreach ($order->shop_order_items as $item): ?>
            <tr>
                <td>
                    <?= h($item->amount) ?>
                </td>
                <td><?= h($item->title); ?></td>
                <td><?= $this->Number->currency($item->item_value_taxed, 'EUR'); ?></td>
                <td><?= $this->Number->currency($item->value_total, 'EUR'); ?></td>
                <td>
                    <?= $this->Html->link(__d('shop','Remove'), ['action' => 'remove', $order->id, $item->id], ['data-icon' => 'trash']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr style="font-weight: bold;">
            <td>&nbsp;</td>
            <td>Gesamt</td>
            <td>&nbsp;</td>
            <td><?= $this->Number->currency($order->items_value_taxed, 'EUR'); ?></td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <div class="owner-order-notice">
        <p>
        Preis inkl. MwSt. zuzüglich Versandkosten
        </p>

        <p>
        Versandkosten: Bis zu einem Gesamtbestellwert von € 250,- werden € 7,50 Versandkosten verrechnet
        (ausgenommen Gartenmöbel, Steinprodukte, Übertöpfe und Weidenprodukte, Brunnen und Griller).
        Gartenmöbel Versandkosten auf Anfrage. Ab € 250,- wird der tatsächliche Aufwand in Rechnung gestellt.
        Über € 2.500,- liefernn wir innerhalb Österreichs frei Haus
        (ausgenommen rabattierte Artikel und Sonderangebote, Steinprodukte, Naturstein-Bodenbeläge und Griller).
        </p>

        <p>
        Die Bezahlung von Gutscheinen ist ausschließlich mit Kreditkarte möglich (ausgenommen American Express).
        Die Gutscheine werden per Post eingeschrieben verschickt, dafür wird eine Versandkostenpauschale
        von € 3,50 eingehoben.
        </p>
    </div>

    <div class="ui divider"></div>


    <div class="ui grid">
        <div class="row">
            <div class="eight wide column">
                <h2>Bestehender Kunde</h2>

                <p>Bitte melden Sie sich an:</p>
                <div class="ui form">
                    <?= $this->Form->create(null); ?>
                    <?= $this->Form->input('email'); ?>
                    <?= $this->Form->input('password'); ?>
                    <div class="actions" style="margin-top: 1em;">
                        <?= $this->Form->submit(__d('shop','Login'), ['class' => 'ui primary button']); ?>
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
            <div class="eight wide column">
                <h2>Neuer Kunde</h2>

                <div class="ui form">
                    <?= $this->Form->create($order); ?>
                    <?= $this->Form->input('billing_first_name', ['label' => __d('shop','First Name')]); ?>
                    <?= $this->Form->input('billing_last_name', ['label' => __d('shop','Last Name')]); ?>
                    <?= $this->Form->input('billing_name', ['label' => __d('shop','Name')]); ?>
                    <?= $this->Form->input('billing_address', ['label' => __d('shop','Address')]); ?>
                    <?= $this->Form->input('billing_taxid', ['label' => __d('shop','Tax Id')]); ?>
                    <?= $this->Form->input('billing_zipcode', ['label' => __d('shop','Zipcode')]); ?>
                    <?= $this->Form->input('billing_city', ['label' => __d('shop','City')]); ?>
                    <?= $this->Form->input('billing_country', ['label' => __d('shop','Country')]); ?>

                    <!--
                    <?= $this->Form->input('customer_phone'); ?>
                    <?= $this->Form->input('customer_fax'); ?>
                    <?= $this->Form->input('customer_email'); ?>
                    <p>
                        Wenn Sie ein zugehöriges Passwort wählen, legen wir für Sie ein
                        Kundenkonto an (Mailadresse und Passwort), mit dem Sie sich beim nächsten
                        Einkauf anmelden können.
                    </p>
                    <?= $this->Form->input('customer_password'); ?>
                    -->

                    <!--
                    <p>Wo haben Sie von uns gehört?</p>
                    -->

                    <!--
                    <?= $this->Form->input('agree_terms', ['label' => 'Ich habe die AGB gelesen und akzeptiert']); ?>
                    -->

                    <div class="actions" style="text-align: right; margin-top: 1em;">
                        <?= $this->Form->submit(__d('shop','Continue'), ['class' => 'ui primary button']); ?>
                    </div>
                    <?= $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>