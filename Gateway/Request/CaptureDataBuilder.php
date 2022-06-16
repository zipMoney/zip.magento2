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
class CaptureDataBuilder extends AbstractDataBuilder
{
    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        $amount = \Magento\Payment\Gateway\Helper\SubjectReader::readAmount($buildSubject);

        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var PaymentDataObjectInterface $paymentDO */
        $paymentDO = $buildSubject['payment'];

        $payment = $paymentDO->getPayment();

        $order = $payment->getOrder();
        $captureAmount = $this->getMultiCurrencyAmount($payment, $amount);
        $payload = $this->_payloadHelper->getCapturePayload($order, $captureAmount);

        $this->_logger->debug("Capture Request:- " . $this->_logger->sanitizePrivateData($payload));

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided.');
        }

        $return['payload'] = $payload;
        $return['txn_id'] = $payment->getLastTransId();
        $addtionalPaymentInfo = $payment->getAdditionalInformation();
        $charge_id = $addtionalPaymentInfo['zip_charge_id'];
        $return['zip_charge_id'] = $charge_id;

        return $return;
    }
}
