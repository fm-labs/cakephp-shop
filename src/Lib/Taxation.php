<?php
declare(strict_types=1);

namespace Shop\Lib;

/**
 * Class Taxation
 *
 * @package Shop\Lib
 */
class Taxation
{
    /**
     * Get Taxrate based on TaxID for Austrian businesses
     * !!THIS FUNCTION IS LIMITED TO USAGE IN AUSTRIA!!
     *
     * @param string $vatId
     * @return float tax rate
     * @todo refactor to work with multiple countries -> use tax classes
     */
    public static function taxrateByVatId($vatId = null)
    {
        if (!$vatId || preg_match("/^AT/i", $vatId)) {
            //B2B Austria
            $taxRate = 20.00;
        } else {
            //B2B EuroZone
            $taxRate = 0.00;
        }

        return $taxRate;
    }

    /**
     * Check if the "Reverse Charge" rule applies. EU only.
     *
     * @todo refactor to work with multiple countries -> use tax classes
     * !!THIS FUNCTION IS LIMITED TO USAGE IN AUSTRIA!!
     *
     * @param null $vatId
     * @param string $myCountry
     * @return bool
     */
    public static function isReverseCharge($vatId = null, $myCountry = "AT")
    {
        if (!$vatId) {
            return false;
        }

        $vatNo = new EuVatNumber($vatId);
        if (!$vatNo->isValid()) {
            return false;
        }

        return $vatNo->getCountryCode() != strtoupper($myCountry);
    }

    /**
     * Calculates Tax from net-value and taxrate
     *
     * @param float|int $net float Net-value
     * @param int $taxRate float Tax rate
     * @return float|int
     */
    public static function tax($net = 0, $taxRate = 0)
    {
        if ($net == 0 || $taxRate == 0) {
            return 0;
        }

        return $net * $taxRate / 100;
    }

    /**
     * Get taxed value from net-value and taxrate
     *
     * @param $net
     * @param $tax_rate
     * @return mixed
     */
    public static function withTax($net, $taxRate)
    {
        return $net + self::tax($net, $taxRate);
    }

    /**
     * Get net value from taxed-value and taxrate
     *
     * @param $gros
     * @param $tax_rate
     * @return float
     */
    public static function withoutTax($taxed, $taxRate)
    {
        return $taxed / (1 + $taxRate / 100);
    }

    /**
     * Get tax from taxed-value
     *
     * @param $taxed
     * @param $tax_rate
     * @return float
     */
    public static function extractTax($taxed, $taxRate)
    {
        return self::withoutTax($taxed, $taxRate) * $taxRate / 100;
    }
}
