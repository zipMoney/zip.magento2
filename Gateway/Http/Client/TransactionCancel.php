<?php

namespace Zip\ZipPayment\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ClientException;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://www.zip.co
 */

class TransactionCancel extends AbstractTransaction implements ClientInterface
{
    /**
     * @var \zipMoney\Api\ChargesApi
     */
    protected $_service = null;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Zip\ZipPayment\Helper\Payload $payloadHelper
     * @param \Zip\ZipPayment\Helper\Logger $logger
     * @param \Zip\ZipPayment\Helper\Data $helper
     * @param \Zip\ZipPayment\Model\Config $config
     * @param \zipMoney\Api\ChargesApi $chargesApi
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        \zipMoney\Api\ChargesApi $chargesApi,
        array $data = []
    ) {

        parent::__construct($context, $encryptor, $payloadHelper, $logger, $helper, $config);

        $this->_service = $chargesApi;
    }

    /**
     * Send the cancel request to Zip
     *
     * @param \Magento\Payment\Gateway\Http\TransferInterface $transferObject
     * @return array
     */
    public function placeRequest(\Magento\Payment\Gateway\Http\TransferInterface $transferObject)
    {
        $request = $transferObject->getBody();
        $zip_charge_id = $request['zip_charge_id'];

        $response = null;

        try {
            $cancel = $this->_service->chargesCancel($zip_charge_id, $this->_helper->generateIdempotencyKey());
            $response = ["api_response" => $cancel];
            $this->_logger->debug("Cancel Response:- " . $this->_logger->sanitizePrivateData($cancel));
        } catch (\zipMoney\ApiException $e) {
            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);

            $response['message'] = $message;
        } finally {
            $log['response'] = $response;
        }

        return $response;
    }
}
