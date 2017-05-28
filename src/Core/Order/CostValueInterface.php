<?php

namespace Shop\Core\Order;


interface CostValueInterface
{
    public function getNetValue();

    public function getTaxValue();

    public function getTotalValue();

    public function getTaxes();
}