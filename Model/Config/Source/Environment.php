<?php

namespace Zip\ZipPayment\Model\Config\Source;

use Zip\ZipPayment\Model\CommonUtil;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Environment implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => CommonUtil::SANDBOX, 'label' => __('Sandbox')],
            ['value' => CommonUtil::PRODUCTION, 'label' => __('Production')]];
    }
}
