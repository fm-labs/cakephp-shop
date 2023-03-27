<?php

namespace Shop\Health;

use Cake\Core\Configure;
use Cupcake\Health\HealthCheckGeneratorInterface;
use Cupcake\Health\HealthStatus;

class Mpay24ConfigHealthCheck implements HealthCheckGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): \Generator
    {
        if (!\Cake\Core\Plugin::isLoaded('FmLabs/Mpay24')) {
            yield HealthStatus::crit("Mpay24 plugin not loaded");
        }

        if (!Configure::check('Mpay24')) {
            yield HealthStatus::crit("Mpay24 not configured");
        }

        $mpay24ConfCheck = function (string $profile) {
            $merchantID = Configure::read(sprintf('Mpay24.%s.merchantId', $profile));
            $soapPassword = Configure::read(sprintf('Mpay24.%s.merchantPassword', $profile));
            $useTestSystem = Configure::read(sprintf('Mpay24.%s.useTestSystem', $profile));
            //$debug = Configure::read(sprintf('Mpay24.%s.debug', $profile));

            if (!$merchantID) {
                yield HealthStatus::crit("Mpay24 profile '{$profile}': Merchant ID missing");
            }
            if (!$soapPassword) {
                yield HealthStatus::crit("Mpay24 profile '{$profile}': Merchant password missing");
            }

            if (str_contains($profile, "test") && !$useTestSystem) {
                yield HealthStatus::crit(sprintf("Mpay24 profile '%s' is assumed to be a testing profile, but is not using the Mpay24 Test API endpoints", $profile));
            }
        };
        foreach (array_keys(Configure::read('Mpay24', [])) as $profile) {
            foreach ($mpay24ConfCheck($profile) as $_result) {
                yield $_result;
            }
        }

        $testMode = (bool)Configure::read('Shop.Payment.testMode');
        if ($testMode && !Configure::read('debug')) {
            yield HealthStatus::crit("Payment Testmode enabled in non-debug environment");
        } elseif ($testMode) {
            yield HealthStatus::warn("Payment Testmode enabled");
        }

        //yield HealthStatus::ok('Ok');
    }
}