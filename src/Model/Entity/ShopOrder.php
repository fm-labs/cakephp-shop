<?php
namespace Shop\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * ShopOrder Entity.
 *
 * @property int $id
 * @property string $uuid
 * @property string $cartid
 * @property string $sessionid
 * @property int $shop_customer_id
 * @property \Shop\Model\Entity\ShopCustomer $shop_customer
 * @property int $nr
 * @property string $title
 * @property float $items_value_net
 * @property float $items_value_tax
 * @property float $items_value_taxed
 * @property string $shipping_type
 * @property float $shipping_value_net
 * @property float $shipping_value_tax
 * @property float $shipping_value_taxed
 * @property float $order_value_total
 * @property string $status
 * @property \Cake\I18n\Time $submitted
 * @property \Cake\I18n\Time $confirmed
 * @property \Cake\I18n\Time $delivered
 * @property \Cake\I18n\Time $invoiced
 * @property \Cake\I18n\Time $payed
 * @property string $customer_notes
 * @property string $staff_notes
 * @property int $billing_address_id
 * @property \Shop\Model\Entity\BillingAddress $billing_address
 * @property string $billing_first_name
 * @property string $billing_last_name
 * @property string $billing_name
 * @property bool $billing_is_company
 * @property string $billing_taxid
 * @property string $billing_zipcode
 * @property string $billing_city
 * @property string $billing_country
 * @property int $shipping_address_id
 * @property \Shop\Model\Entity\ShippingAddress $shipping_address
 * @property bool $shipping_use_billing
 * @property string $shipping_first_name
 * @property string $shipping_last_name
 * @property string $shipping_name
 * @property bool $shipping_is_company
 * @property string $shipping_zipcode
 * @property string $shipping_city
 * @property string $shipping_country
 * @property string $customer_phone
 * @property string $customer_email
 * @property string $customer_ip
 * @property string $payment_type
 * @property string $payment_info_1
 * @property string $payment_info_2
 * @property string $payment_info_3
 * @property bool $is_temporary
 * @property bool $is_storno
 * @property bool $is_deleted
 * @property bool $agree_terms
 * @property bool $agree_newsletter
 * @property string $locale
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time $created
 * @property \Shop\Model\Entity\ShopCart[] $shop_carts
 * @property \Shop\Model\Entity\ShopOrderItem[] $shop_order_items
 */
class ShopOrder extends Entity
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

    protected $_virtual = [
        'nr_formatted',
        'is_billing_selected',
        'is_shipping_selected',
        'is_payment_selected'
    ];

    protected function _getNrFormatted()
    {
        if (isset($this->_properties['nr'])) {

            $prefix = "ORDER";
            $suffix = "";
            $nr = $this->_properties['nr'];
            $zeroFill = 5;

            if ($zeroFill > 0) {
                $nrFill = str_repeat("0", $zeroFill) . (string) $nr;
                $nr = substr($nrFill, $zeroFill * -1);
            }

            return sprintf("%s%s%s", $prefix, $nr, $suffix);
        }

        return null;
    }

    protected function _getIsBillingSelected()
    {
        if (
            isset($this->_properties['billing_first_name'])
            && isset($this->_properties['billing_last_name'])
            && isset($this->_properties['billing_street'])
            && isset($this->_properties['billing_zipcode'])
            && isset($this->_properties['billing_city'])
            && isset($this->_properties['billing_country'])
        ) {
            return true;
        }

        return false;
    }

    protected function _getIsShippingSelected()
    {
        if (!isset($this->_properties['shipping_type']) || empty($this->_properties['shipping_type']) ) {
            return false;
        }

        if ($this->_properties['shipping_use_billing']) {
            return $this->is_billing_selected;
        }

        if (
            isset($this->_properties['shipping_first_name'])
            && isset($this->_properties['shipping_last_name'])
            && isset($this->_properties['shipping_street'])
            && isset($this->_properties['shipping_zipcode'])
            && isset($this->_properties['shipping_city'])
            && isset($this->_properties['shipping_country'])
        ) {
            return true;
        }

        return false;
    }

    protected function _getIsPaymentSelected()
    {
        $paymentMethods = Configure::read('Shop.PaymentMethods');

        if (
            isset($this->_properties['payment_type'])
            && array_key_exists($this->_properties['payment_type'], $paymentMethods)
        ) {
            return true;
        }

        return false;
    }

    public function update()
    {
        $orderItems = TableRegistry::get('Shop.ShopOrderItems')
            ->find()
            ->where(['shop_order_id' => $this->id])
            ->all()
            ->toArray();

        // items value
        $itemsNet = $itemsTax = $itemsTaxed = 0;
        array_walk($orderItems, function ($item) use (&$itemsNet, &$itemsTax, &$itemsTaxed) {
            $itemsNet += $item->value_net;
            $itemsTax += $item->value_tax;
            $itemsTaxed += $item->value_total;
        });

        $this->items_value_net = $itemsNet;
        $this->items_value_tax = $itemsTax;
        $this->items_value_taxed = $itemsTaxed;

        $this->order_value_tax = $itemsTax;
        $this->order_value_total = $itemsTaxed;
    }
}
