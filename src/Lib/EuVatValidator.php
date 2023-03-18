<?php
declare(strict_types=1);

namespace Shop\Lib;

use Cake\Log\Log;
use SoapClient;

/**
 * Class EuVatValidator
 *
 * https://ec.europa.eu/taxation_customs/vies/faq.html
 * https://ec.europa.eu/taxation_customs/vies/technicalInformation.html
 * https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl (Webservice API)
 * https://ec.europa.eu/taxation_customs/vies/checkVatTestService.wsdl (Test Webservice API)
 * https://ec.europa.eu/taxation_customs/vies/vatRequest.html
 *
 * @package Shop\Lib
 */
class EuVatValidator
{
    //static public $wsdl = "https://ec.europa.eu/taxation_customs/vies/checkVatTestService.wsdl";
    public static $wsdl = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";

    /**
     * @var \SoapClient
     */
    protected $_client;

    /**
     * @param $vatId
     * @return bool
     */
    public function checkVat($vatId)
    {
        $vatNumber = new EuVatNumber($vatId);
        if (!$vatNumber->isValid()) {
            return false;
        }

        try {
            $this->_client = new SoapClient(static::$wsdl);
            $result = $this->_client->checkVat([
                'countryCode' => $vatNumber->getCountryCode(),
                'vatNumber' => $vatNumber->getNumber(),
            ]);

            if (!$result) {
                throw new \RuntimeException("No validation result");
            }

            if ($result && is_object($result) && $result->valid == true) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            Log::error('EuVatValidator: ' . $ex->getMessage(), ['shop']);

            return true; //@TODO In case of an internal error, let validation pass.
        }
    }
}
