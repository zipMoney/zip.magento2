<?php

namespace Zip\ZipPayment\Model\Config\Source;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class ApiSource implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'global', 'label' => __('Global')], ['value' => 'default', 'label' => __('AU region')]];
    }
}
