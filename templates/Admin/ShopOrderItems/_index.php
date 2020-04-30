<?php $this->Breadcrumbs->add(__d('shop','Shop Order Items')); ?>
<?php $this->loadHelper('Cupcake.Status'); ?>
<?php $this->Toolbar->addLink(__d('shop','New {0}', __d('shop','Shop Order Item')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<?php $this->Toolbar->addLink(
    __d('shop','List {0}', __d('shop','Shop Orders')),
    ['controller' => 'ShopOrders', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('shop','New {0}', __d('shop','Shop Order')),
    ['controller' => 'ShopOrders', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<div class="shopOrderItems index">


    <?= $this->cell('Admin.DataTable', [[
        'paginate' => true,
        'model' => 'Shop.ShopOrderItems',
        'data' => $shopOrderItems,
        'class' => 'table table-condensed table-striped table-hover',
        'fieldsWhitelist' => true,
        'fields' => [
            'id' => [
                'formatter' => function($val, $row) {
                    return $this->Html->link($val, ['action' => 'view', $row->id]);
                }
            ],
            'product_sku' => [
                'formatter' => function($val, $row) {
                    return ($val) ?: $row->getProduct()->getSku();
                }
            ],
            'product_title' => [
                'formatter' => function($val, $row) {
                    $val = ($val) ?: $row->getProduct()->getTitle();
                    return $this->Html->link($val, $row->getProduct()->getAdminUrl(), ['class' => 'link-modal-frame']);
                }
            ],
            'amount' => ['formatter' => function($val, $row) {
                return sprintf("%d %s", $val, $row->unit);
            }],
            /*
            'value_tax' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            'value_net' => ['formatter' => function($val, $row) use ($shopOrder) {
                return $this->Number->currency($val, $shopOrder->currency);
            }],
            */
            'value' => ['title' => __d('shop','Total'), 'formatter' => function($val, $row) {
                $val = ($val) ?: $row->value_net + $row->value_tax;
                return $this->Number->currency($val, $row->currency);

            }],
            'status' => ['formatter' => function($val) {
                return $this->Status->label($val);
            }],
        ],
        'rowActions' => [
            [__d('shop','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('shop','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            //[__d('shop','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('shop','Are you sure you want to delete # {0}?', ':id')]]
        ],
        'reduce' => [
            'value' => [
                'callable' => function($val, $row, &$stack) {
                    if (!isset($stack['value'])) {
                        $stack['value'] = 0;
                    }
                    $stack['value'] += ($row->value_net + $row->value_tax);
                },
                'formatter' => function($val) {
                    return $this->Number->currency($val, 'EUR');
                }]
        ]
    ]]);
    ?>
</div>
