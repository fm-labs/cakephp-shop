<?php

namespace Shop\Core\Product;

/**
 * Interface ShopProductInterface
 *
 * @package Shop\Core\Product
 */
interface ShopProductInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getSku();

    /**
     * @return string
     * @deprecated
     */
    public function getUnit();

    /**
     * Net base product price
     * @return float
     */
    public function getPrice();

    /**
     * Absolute tax rate
     * @return float
     * @TODO Replace with getTaxClass()
     */
    public function getTaxRate();

    /**
     * @return ?
     */
    //public function getTaxClass();

    /**
     * @return bool
     */
    public function isBuyable();
}
