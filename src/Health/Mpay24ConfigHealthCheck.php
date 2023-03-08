<?php

namespace Shop\Health;

use Cake\Core\Configure;
use Cupcake\Health\HealthCheckInterface;
use Cupcake\Health\HealthStatus;

class Mpay24ConfigHealthCheck implements HealthCheckInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): HealthStatus
    {
        if (Configure::read('Shop.Payment.testMode')) {
            $testMode = true;
        }
        if ($testMode) {
            $merchantID = Configure::read('Mpay24.Test.merchantID');
            $soapPassword = Configure::read('Mpay24.Test.soapPassword');
        } else {
            $merchantID = Configure::read('Mpay24.merchantID');
            $soapPassword = Configure::read('Mpay24.soapPassword');
        }

        if (!$merchantID) {
            return HealthStatus::crit("Mpay24 Merchant ID missing");
        }
        if (!$soapPassword) {
            return HealthStatus::crit("Mpay24 Merchant password missing");
        }
        return HealthStatus::ok('Ok');
    }
}