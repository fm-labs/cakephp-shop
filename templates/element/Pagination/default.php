<div class="paginator">
    <div class="ui pagination menu">
        <?= $this->Paginator->prev(__d('shop', 'previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__d('shop', 'next')) ?>

        <div class="item">
            <?= $this->Paginator->counter() ?>
        </div>
    </div>
</div>