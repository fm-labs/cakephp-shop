<?php

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
    static public function taxrateByVatId($vatId = null)
    {
        if (!$vatId || preg_match("/^AT/i",$vatId)) {
            //B2B Austria
            $tax_rate = 20.00;
        } else {
            //B2B EuroZone
            $tax_rate = 0.00;
        }
        return $tax_rate;
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
    static public function isReverseCharge($vatId = null, $myCountry = "AT")
    {
        if (!$vatId)
            return false;

        return !preg_match(sprintf("/^%s/i",strtoupper($myCountry)),trim($vatId));
    }

    /**
     * Calculates Tax from net-value and taxrate
     *
     * @param float|int $net float Net-value
     * @param float|int $tax_rate float Tax rate
     * @return float|int
     */
    static public function tax($net = 0, $tax_rate = 0)
    {
        if ($net == 0 || $tax_rate == 0)
            return 0;

        return $net * $tax_rate / 100;
    }

    /**
     * Get taxed value from net-value and taxrate
     *
     * @param $net
     * @param $tax_rate
     * @return mixed
     */
    static public function withTax($net, $tax_rate)
    {
        return $net + self::tax($net, $tax_rate);
    }

    /**
     * Get net value from taxed-value and taxrate
     *
     * @param $gros
     * @param $tax_rate
     * @return float
     */
    static public function withoutTax($taxed, $tax_rate)
    {
        return $taxed / (1 + $tax_rate / 100);
    }

    /**
     * Get tax from taxed-value
     *
     * @param $taxed
     * @param $tax_rate
     * @return float
     */
    static public function extractTax($taxed, $tax_rate)
    {
        return self::withoutTax($taxed, $tax_rate) * ($tax_rate / 100);
    }


}