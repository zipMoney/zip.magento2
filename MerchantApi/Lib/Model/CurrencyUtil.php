<?php
/**
 * CurrencyUtil
 *
 * @category Class
 * @package  zip
 * @author    Zip Plugin Team <integration@zip.co>
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Model;

class CurrencyUtil
{
    const CURRENCY_AUD = 'AUD';
    const CURRENCY_NZD = 'NZD';
    const CURRENCY_GBP = 'GBP';
    const CURRENCY_USD = 'USD';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    private static function getAllowedCurrencyList()
    {
        return array(
            self::CURRENCY_AUD,
            self::CURRENCY_NZD,
            self::CURRENCY_USD,
            self::CURRENCY_GBP,
        );
    }

    public static function isValidCurrency($currency)
    {
        $result = array(
            'valid' => true,
            'message' => '',
        );
        $allowed_values = self::getAllowedCurrencyList();
        if (!in_array($currency, $allowed_values)) {
            $result['valid'] = false;
            $result['message'] = "invalid value for 'currency', must be one of '".implode("','",$allowed_values)."'.";
        }
        return $result;
    }
}
