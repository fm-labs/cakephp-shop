<?php
namespace Shop\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Shop\Lib\Shop;

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
        'is_payment_selected',
        'billing_address_formatted',
        'selected_address_formatted',
        'order_value_tax',
        'billing_address',
        'shipping_address',
    ];

    protected function _getShopCustomer()
    {
        if (!isset($this->_properties['shop_customer'])) {
            $this->_properties['shop_customer'] = TableRegistry::get('Shop.ShopCustomers')
                ->find()
                ->where(['ShopCustomers.id' => $this->shop_customer_id])
                ->first();
        }
        return $this->_properties['shop_customer'];
    }

    public function getOrderAddress($addressType)
    {
        $key = 'address_' . strtolower($addressType);
        if (!isset($this->_properties[$key])) {
            $this->_properties[$key] = TableRegistry::get('Shop.ShopOrderAddresses')
                ->find()
                ->contain(['Countries'])
                ->where(['shop_order_id' => $this->id, 'type' => $addressType])
                ->first();
        }
        return $this->_properties[$key];
    }

    public function getBillingAddress()
    {
        return $this->getOrderAddress('B');
    }

    protected function _getBillingAddress()
    {
        return $this->getBillingAddress();
    }

    public function getShippingAddress()
    {
        return $this->getOrderAddress('S');
    }

    protected function _getShippingAddress()
    {
        return $this->getShippingAddress();
    }

    public function calculateItems()
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

    protected function _getNrFormatted()
    {
        if (isset($this->_properties['nr'])) {

            $orderCfg = Shop::config('Order');

            $prefix = $orderCfg['nrPrefix'];
            $suffix = $orderCfg['nrSuffix'];
            $zeroFill = $orderCfg['nrZerofill'];
            $grp = $this->_properties['ordergroup'];
            $nr = $this->_properties['nr'];

            if ($zeroFill > 0) {
                $nrFill = str_repeat("0", $zeroFill) . (string) $nr;
                $nr = substr($nrFill, $zeroFill * -1);
            }

            return $prefix . $grp . $nr . $suffix;
        }

        return null;
    }

    /**
     * Get the currency for this order
     *
     * @return string
     * @todo Implement currency support for orders
     */
    protected function _getCurrency()
    {
        return 'EUR';
    }

    /**
     * Get the base currency for this order
     *
     * @return string
     * @todo Implement currency support for orders
     */
    protected function _getBaseCurrency()
    {
        return 'EUR';
    }

    /**
     * @return string
     * @deprecated
     */
    protected function _getBillingAddressFormatted()
    {
        return ShopAddress::formatAddress(ShopAddress::extractAddress($this->_properties, 'billing_'));
    }

    /**
     * @return string
     * @deprecated
     */
    protected function _getShippingAddressFormatted()
    {
        return ShopAddress::formatAddress(ShopAddress::extractAddress($this->_properties, 'shipping_'));
    }

    /**
     * @return bool
     * @deprecated
     */
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

    /**
     * @return bool|mixed
     * @deprecated
     */
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

    /**
     * @return bool
     * @deprecated
     */
    protected function _getIsPaymentSelected()
    {
        $paymentMethods = Configure::read('Shop.Payment.Engines');

        if (
            isset($this->_properties['payment_type'])
            && array_key_exists($this->_properties['payment_type'], $paymentMethods)
        ) {
            return true;
        }

        return false;
    }


    protected function _getCcBrand()
    {
        if ($this->payment_type == 'credit_card_internal' && $this->payment_info_1) {
            list($brand,$number) = explode(':', $this->payment_info_1);
            return $brand;
        }
    }

    protected function _getCcNumber()
    {
        if ($this->payment_type == 'credit_card_internal' && $this->payment_info_1) {
            list($brand,$number) = explode(':', $this->payment_info_1);
            return $number;
        }
    }

    protected function _getCcHolderName()
    {
        if ($this->payment_type == 'credit_card_internal') {
            return $this->payment_info_2;
        }
    }

    protected function _getCcExpiresAt()
    {
        if ($this->payment_type == 'credit_card_internal') {
            return $this->payment_info_3;
        }
    }

    protected function _setOrderValueTax($val)
    {
        return $val;
    }

    protected function _getOrderValueTax()
    {
        if (!isset($this->_properties['order_value_tax'])) {
            $this->_properties['order_value_tax'] = $this->_properties['items_value_tax'] + $this->_properties['shipping_value_tax'];
        }
        return $this->_properties['order_value_tax'];
    }

}
