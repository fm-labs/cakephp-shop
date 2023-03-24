<?php if (!isset($calculator)): ?>
    No calculator set
    <?php return; ?>
<?php endif; ?>
<div class="cost-calculator">

    <table class="table">
        <?php if (!isset($noHeader)): ?>
        <tr>
            <th>Name</th>
            <th>Net</th>
            <th>Tax</th>
            <th>Total</th>
        </tr>
        <?php endif; ?>
        <?php foreach ($calculator->getValues() as $name => $cost): ?>
            <tr>
                <td><?= h($name); ?></td>
                <td><?= $cost->getNetValue(); ?></td>
                <td><?= $cost->getTaxValue(); ?></td>
                <td><?= $cost->getTotalValue(); ?></td>
            </tr>


        <?php endforeach; ?>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td><?= $calculator->getNetValue(); ?></td>
            <td><?= $calculator->getTaxValue(); ?></td>
            <td><?= $calculator->getTotalValue(); ?></td>
        </tr>
        <!--
        <tr>
            <td><strong>CHECK</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?= $calculator->getNetValue() + $calculator->getTaxValue(); ?></td>
        </tr>
        -->
    </table>


    <?php //debug($calculator->toArray()); ?>

</div>