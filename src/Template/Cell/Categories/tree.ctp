<div class="cell shop categories tree">
    <ul>
        <?php foreach($this->get('treeList') as $id => $label): ?>
        <li><?= $this->Html->link($label, [ 'plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'view', $id]); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php debug($treeList); ?>