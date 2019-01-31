<?php

namespace Shop\Service;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;
use Shop\Event\CartEvent;
use Shop\Lib\Shop;

class ShopRulesService implements EventListenerInterface
{

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'Shop.Cart.beforeItemUpdate' => 'cartBeforeItemUpdate'
        ];
    }

    public function cartBeforeItemUpdate(CartEvent $event)
    {
        $item = $event->getItem();
        $data = $event->getUserData();
        $customer = $event->getCustomer();

        $product = $event->getProduct();
        $priceNet = $product->getPrice();

        if (Shop::config('Shop.CustomerDiscounts.enabled') == true && $customer) {
            $ShopCustomerDiscounts = TableRegistry::get('Shop.ShopCustomerDiscounts');

            // find customer discounts for specific product
            $customerDiscount = $ShopCustomerDiscounts->find()->where([
                'shop_customer_id' => $customer->id,
                'shop_product_id' => $product->id,
                'is_published' => true,
                'min_amount <=' => $data['amount']
            ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();

            // find customer discounts for parent product, if no product discount found
            if (!$customerDiscount && $product['parent_id'] > 0 /* && $row['type'] == "child" */) {
                $customerDiscount = $ShopCustomerDiscounts->find()->where([
                    'shop_customer_id' => $customer->id,
                    'shop_product_id' => $product['parent_id'],
                    'is_published' => true,
                    'min_amount <=' => $data['amount']
                ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
            }

            // find customer discounts, if no product discount found
            if (!$customerDiscount) {
                $customerDiscount = $ShopCustomerDiscounts->find()->where([
                    'shop_customer_id' => $customer->id,
                    'shop_product_id IS' => null,
                    'is_published' => true,
                    'min_amount <=' => $data['amount']
                ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
            }

            // apply customer discount
            if ($customerDiscount) {
                $discountValue = 0;
                switch ($customerDiscount->valuetype) {
                    case "percent":
                        $discountValue = $priceNet * ($customerDiscount->value / 100);
                        break;
                    case "value":
                        $discountValue = $customerDiscount->value;
                        break;
                    default:
                        //@TODO Handle unsupported customer discount value type
                        break;
                }

                if ($discountValue > 0) {
                    // make sure discount is not higher than original price
                    $discountValue = min($priceNet, $discountValue);
                    $priceNet = $priceNet - $discountValue;
                }
            }
        }

        $event->data['data']['item_value_net'] = $priceNet;
    }
}
