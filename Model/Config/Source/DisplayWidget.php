<?php

namespace Zip\ZipPayment\Model\Config\Source;

use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

/**
 * Used in creating options for sandbox|production config value selection
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
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
            ['value' => CommonUtil::INLINE, 'label' => __('Inline')]];
    }
}
