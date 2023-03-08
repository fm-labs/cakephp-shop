<?php

namespace Shop\Logging;

use Cake\Log\Log;
use Shop\Model\Entity\ShopOrderTransaction;

trait TransactionLoggingTrait
{
    protected function logTransaction(ShopOrderTransaction $t, $msg, $level = 'info')
    {
        $msg = sprintf("Payment::Transaction [%s] %s: %s", $t->engine, $t->id, $msg);
        Log::write($level, $msg, ['shop', 'payment']);
    }
}