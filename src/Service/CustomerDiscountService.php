<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Collection\Iterator\MapReduce;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Shop\Event\CartEvent;
use Shop\Lib\Shop;

class CustomerDiscountService implements EventListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function implementedEvents(): array
    {
        return [
            'Model.initialize' => ['callable' => 'modelInitialize'],
            'Model.beforeFind' => ['callable' => 'modelBeforeFind'],
            //'Shop.Cart.beforeItemUpdate' => 'cartBeforeItemUpdate',
        ];
    }

    public function modelInitialize(EventInterface $event)
    {
        ///** @var \Shop\Model\Table\ShopProductsTable $table */
        $table = $event->getSubject();
        if ($table instanceof \Shop\Model\Table\ShopProductsTable) {

        }
    }

    /**
     * 'beforeFind' callback
     *
     * Applies a MapReduce to the query, which resolves attachment info
     * if an attachment field is present in the query results.
     *
     * @param \Cake\Event\Event $event
     * @param \Cake\ORM\Query $query
     * @param array $options
     * @param $primary
     */
    public function modelBeforeFind(\Cake\Event\EventInterface $event, Query $query, $options, $primary)
    {
        ///** @var \Shop\Model\Table\ShopProductsTable $table */
        $table = $event->getSubject();
        if (!$table instanceof \Shop\Model\Table\ShopProductsTable) {
            return;
        }

        //if (!isset($options['skip_price']) || $options['skip_price'] === false) {
        //    return;
        //}

        if (!$primary) {
            return;
        }

        $mapper = function ($row, $key, MapReduce $mapReduce) use ($options) {
            if (isset($row['price_net'])) {
                $row['price_net_original'] = $row['price_net'];
            }

            if (Shop::config('Shop.CustomerDiscounts.enabled') == true && isset($options['for_customer'])) {
                $ShopCustomerDiscounts = TableRegistry::getTableLocator()->get('Shop.ShopCustomerDiscounts');

                //debug($options['for_customer']);

                // find customer discounts for specific product
                $customerDiscount = $ShopCustomerDiscounts->find()->where([
                    'shop_customer_id' => $options['for_customer'],
                    'shop_product_id' => $row['id'],
                    'is_published' => true,
                    'min_amount <=' => 1,
                ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();

                // find customer discounts for parent product, if no product discount found
                if (!$customerDiscount && $row['parent_id'] > 0 /* && $row['type'] == "child" */) {
                    $customerDiscount = $ShopCustomerDiscounts->find()->where([
                        'shop_customer_id' => $options['for_customer'],
                        'shop_product_id' => $row['parent_id'],
                        'is_published' => true,
                        'min_amount <=' => 1,
                    ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
                }

                // find customer discounts, if no product discount found
                if (!$customerDiscount) {
                    $customerDiscount = $ShopCustomerDiscounts->find()->where([
                        'shop_customer_id' => $options['for_customer'],
                        'shop_product_id IS' => null,
                        'is_published' => true,
                        'min_amount <=' => 1,
                    ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
                }

                // apply customer discount
                if ($customerDiscount) {
                    //debug($customerDiscount);
                    switch ($customerDiscount->valuetype) {
                        case "percent":
                            $discount = $row['price_net_original'] * $customerDiscount->value / 100;
                            break;

                        case "value":
                            $discount = $customerDiscount->value;
                            break;

                        default:
                            //@TODO Handle unsupported customer discount value type
                    }

                    // make sure discount is not higher than original price
                    if (isset($discount)) {
                        //debug($row['price_net_original']);
                        //debug($discount);
                        $discount = min($row['price_net_original'], $discount);
                        $row['price_net'] = $row['price_net_original'] - $discount;
                    }
                }
            }

            $mapReduce->emitIntermediate($row, $key);
        };

        $reducer = function ($bucket, $name, MapReduce $mapReduce) {
            $mapReduce->emit($bucket[0], $name);
        };

        $query->mapReduce($mapper, $reducer);
    }

    public function cartBeforeItemUpdate(CartEvent $event)
    {
        $item = $event->getItem();
        $data = $event->getUserData();
        $customer = $event->getCustomer();

        $product = $event->getProduct();
        $priceNet = $product->getPrice();

        if (Shop::config('Shop.CustomerDiscounts.enabled') == true && $customer) {
            debug("discounts");
            $ShopCustomerDiscounts = TableRegistry::getTableLocator()->get('Shop.ShopCustomerDiscounts');

            // find customer discounts for specific product
            $customerDiscount = $ShopCustomerDiscounts->find()->where([
                'shop_customer_id' => $customer->id,
                'shop_product_id' => $product->id,
                'is_published' => true,
                'min_amount <=' => $data['amount'],
            ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
            //debug($customerDiscount);

            // find customer discounts for parent product, if no product discount found
            if (!$customerDiscount && $product['parent_id'] > 0 /* && $row['type'] == "child" */) {
                $customerDiscount = $ShopCustomerDiscounts->find()->where([
                    'shop_customer_id' => $customer->id,
                    'shop_product_id' => $product['parent_id'],
                    'is_published' => true,
                    'min_amount <=' => $data['amount'],
                ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
                //debug($customerDiscount);
            }

            // find customer discounts, if no product discount found
            if (!$customerDiscount) {
                $customerDiscount = $ShopCustomerDiscounts->find()->where([
                    'shop_customer_id' => $customer->id,
                    'shop_product_id IS' => null,
                    'is_published' => true,
                    'min_amount <=' => $data['amount'],
                ])->order(['ShopCustomerDiscounts.min_amount' => 'DESC'])->first();
                //debug($customerDiscount);
            }

            // apply customer discount
            if ($customerDiscount) {
                $discountValue = 0;
                switch ($customerDiscount->valuetype) {
                    case "percent":
                        $discountValue = $priceNet * $customerDiscount->value / 100;
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

        $event->getData('data')['item_value_net'] = $priceNet;
    }
}
