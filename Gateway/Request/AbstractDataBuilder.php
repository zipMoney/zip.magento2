<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zip\ZipPayment\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://www.zip.co
 */
abstract class AbstractDataBuilder implements BuilderInterface
{
    /**
     * @var \Zip\ZipPayment\Helper\Payload
     */
    protected $_payloadHelper;
    /**
     * @var \Zip\ZipPayment\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    public function __construct(
        \Magento\Sales\Model\Order $order,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Helper\Logger $logger
    ) {
        $this->_payloadHelper = $payloadHelper;
        $this->_helper = $helper;
        $this->_logger = $logger;
        $this->_order = $order;
    }

    public function getMultiCurrencyAmount($payment, $baseAmount)
    {
        $order = $payment->getOrder();
        $grandTotal = $order->getGrandTotal();
        $baseGrandTotal = $order->getBaseGrandTotal();

        $rate = $order->getBaseToOrderRate();
        if ($rate == 0) {
            $rate = 1;
        }

        // Full refund, ignore currency rate in case it changed
        if ($baseAmount == $baseGrandTotal) {
            return $grandTotal;
        } elseif (is_numeric($rate)) {
            // Partial refund, consider currency rate but don't refund more than the original amount
            return min(round($baseAmount * $rate, 2), $grandTotal);
        } else {
            // Not a multicurrency refund
            return $baseAmount;
        }
    }
}
