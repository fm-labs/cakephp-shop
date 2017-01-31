<?php

namespace Shop\Core\Product;


interface ShopProductInterface
{
    public function getTitle();
    public function getSku();

    public function isBuyable();
}