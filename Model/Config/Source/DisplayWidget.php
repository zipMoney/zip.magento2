<?php

namespace Zip\ZipPayment\Model\Config\Source;

use phpDocumentor\Reflection\Types\Self_;
use \Zip\ZipPayment\Logger\Logger as ZipMoneyLogger;
use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

/**
 * Used in creating options for sandbox|production config value selection
 *
 */
class DisplayWidget implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {

        return [['value' => CommonUtil::IFRAME, 'label' => __('Iframe')],
            ['value' => CommonUtil::INLINE, 'label' => __('In Line')]];

    }
}
