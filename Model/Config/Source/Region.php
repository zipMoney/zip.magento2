<?php

namespace Zip\ZipPayment\Model\Config\Source;

use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Region implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return CommonUtil::getAvailableRegionList();
    }
}
