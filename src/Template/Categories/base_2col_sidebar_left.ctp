<div class="shop categories view base container">
    <div class="row">
        <div class="col-md-3">
            <?= $this->fetch('col-left', $this->element('Shop.Categories/view/sidebar')); ?>
        </div>
        <div class="col-md-9">
            <?= $this->fetch('content'); ?>
        </div>
    </div>
</div>
