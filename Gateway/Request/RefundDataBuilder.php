<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zip\ZipPayment\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class RefundDataBuilder extends AbstractDataBuilder
{
    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        //$response = \Magento\Payment\Gateway\Helper\SubjectReader::readResponse($buildSubject);

        $amount = \Magento\Payment\Gateway\Helper\SubjectReader::readAmount($buildSubject);

        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        // @var PaymentDataObjectInterface $paymentDO
        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();
        $refundAmount = $this->getMultiCurrencyAmount($payment, $amount);
        $order = $payment->getOrder();
        $payload = $this->_payloadHelper->getRefundPayload($order, $refundAmount, $order->getIncrementId() . strtotime("now"));
        $this->_logger->debug(
            "Refund Request:- "
            . $this->_helper->json_encode($payload)
        );

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided.');
        }

        $return['payload'] = $payload;
        $return['store_id'] = $order->getStoreId();
        $return['txn_id'] = $payment->getLastTransId();

        return $return;
    }

    public function getMultiCurrencyAmount($payment, $baseAmount)
    {
        $order = $payment->getOrder();
        $grandTotal = $order->getGrandTotal();
        $baseGrandTotal = $order->getBaseGrandTotal();

        $rate = $order->getBaseToOrderRate();
        if ($rate == 0) $rate = 1;

        // Full refund, ignore currency rate in case it changed
        if ($baseAmount == $baseGrandTotal)
            return $grandTotal;
        // Partial refund, consider currency rate but don't refund more than the original amount
        else if (is_numeric($rate))
            return min($baseAmount * $rate, $grandTotal);
        // Not a multicurrency refund
        else
            return $baseAmount;
    }
}
