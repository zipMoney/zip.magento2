<?php
/**
 * CommonUtil
 *
 * @category Class
 * @package  zip
 * @author    Zip Plugin Team <integration@zip.co>
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Model;

class CommonUtil
{


    // Zip widget will display inside the iframe
    const IFRAME = 'iframe';
    // Zip widget will display inline
    const INLINE = 'inline';

    // environment
    const SANDBOX = 'sandbox';
    const PRODUCTION = 'production';

    /**
     * @return array[]
     */
    public static function getEnvironmentList()
    {
        return [['value' => self::SANDBOX, 'label' => __('Sandbox')],
            ['value' => self::PRODUCTION, 'label' => __('Production')]];
    }
}
