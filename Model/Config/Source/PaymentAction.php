<?php
namespace Zip\ZipPayment\Model\Config\Source;

/**
 * @author    Zip Plugin Team <integratios@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class PaymentAction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'authorise', 'label' => __('Authorise')], ['value' => 'capture', 'label' => __('Capture')]];
    }
}
