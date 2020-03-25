<?php
declare(strict_types=1);

namespace Shop\Core\Order;

interface CostValueInterface
{
    public function getNetValue();

    public function getTaxValue();

    public function getTotalValue();

    public function getTaxes();
}
