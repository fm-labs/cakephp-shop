<?php
declare(strict_types=1);

namespace Shop\Model\Entity;

use Cake\ORM\Entity;

/**
 * ShopOrderInvoice Entity.
 *
 * @property int $id
 * @property int $parent_id
 * @property \Shop\Model\Entity\ShopOrderInvoice $parent_shop_order_invoice
 * @property int $shop_order_id
 * @property \Shop\Model\Entity\ShopOrder $shop_order
 * @property string $group
 * @property int $nr
 * @property \Cake\I18n\Time $date_invoice
 * @property string $title
 * @property float $value_total
 * @property int $status
 * @property bool $customer_notify_sent
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Shop\Model\Entity\ShopOrderInvoice[] $child_shop_order_invoices
 */
class ShopOrderInvoice extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
