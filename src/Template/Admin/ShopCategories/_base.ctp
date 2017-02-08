<?php
$this->assign('contentClass', 'content-nopad')
?>
<div class="tree-index row">
    <div class="tree-index-menu col-md-3">
        <?= $this->cell('Shop.CategoriesTree::adminMenu'); ?>
    </div>
    <div class="tree-index-body col-md-9">
        <?= $this->fetch('content'); ?>
    </div>
</div>