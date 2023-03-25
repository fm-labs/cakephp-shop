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
        if (!\Cake\Core\Plugin::isLoaded('FmLabs/Mpay24')) {
            return HealthStatus::crit("Mpay24 plugin not loaded");
        }

        if (Configure::read('Shop.Payment.testMode') || Configure::read('Mpay24.useTestSystem')) {
            $testMode = true;
        }
        if ($testMode) {
            $merchantID = Configure::read('Mpay24.testing.merchantId');
            $soapPassword = Configure::read('Mpay24.testing.merchantPassword');
        } else {
            $merchantID = Configure::read('Mpay24.production.merchantId');
            $soapPassword = Configure::read('Mpay24.production.merchantPassword');
        }

        if (!$merchantID) {
            return HealthStatus::crit("Mpay24 Merchant ID missing");
        }
        if (!$soapPassword) {
            return HealthStatus::crit("Mpay24 Merchant password missing");
        }

        if ($testMode && !Configure::read('debug')) {
            return HealthStatus::crit("Mpay24 Testmode enabled in non-debug environment");
        } elseif ($testMode) {
            return HealthStatus::warn("Mpay24 Testmode enabled");
        }

        return HealthStatus::ok('Ok');
    }
}