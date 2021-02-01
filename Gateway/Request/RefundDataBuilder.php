<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zip\ZipPayment\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://www.zip.co
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
        $payload = $this->_payloadHelper->getRefundPayload(
            $order,
            $refundAmount,
            $order->getIncrementId() . strtotime("now")
        );
        $this->_logger->debug(
            "Refund Request:- "
            . $this->_helper->jsonEncode($payload)
        );

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided.');
        }

        $return['payload'] = $payload;
        $return['store_id'] = $order->getStoreId();
        $return['txn_id'] = $payment->getLastTransId();

        return $return;
    }
}
