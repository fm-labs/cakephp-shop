<?php

namespace Shop\Core\Payment\Engine\Mpay24;

use \Mpay24\Mpay24Order as BaseMpay24Order;

class Mpay24Order extends BaseMpay24Order {

    public function validate()
    {
        $schemaPath = dirname(__FILE__) . DS . "MDXI.xsd";
        if (!$this->document->schemaValidate($schemaPath)) {
            return false;
        }
        return true;
    }

    public function getDocument()
    {
        return $this->document;
    }
}