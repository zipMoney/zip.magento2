<?php

namespace Zip\ZipPayment\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://www.zip.co
 */
class TransactionCapture extends AbstractTransaction implements ClientInterface
{
    protected $_service = null;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        \Zip\ZipPayment\MerchantApi\Lib\Api\ChargesApi $chargesApi,
        array $data = []
    ) {
        parent::__construct($context, $encryptor, $payloadHelper, $logger, $helper, $config);

        $this->_service = $chargesApi;
    }

    /**
     * @param \Magento\Payment\Gateway\Http\TransferInterface $transferObject
     * @return array
     */
    public function placeRequest(\Magento\Payment\Gateway\Http\TransferInterface $transferObject)
    {
        $request = $transferObject->getBody();
        $payload = $request['payload'];
        $zip_charge_id = $request['zip_charge_id'];

        $response = null;

        try {

            $charge = $this->_service->chargesCapture(
                $zip_charge_id,
                $payload,
                $this->_helper->generateIdempotencyKey()
            );
            $response = ["api_response" => $charge];
            $this->_logger->debug("Capture Charge Response:- " . $this->_logger->sanitizePrivateData($charge));
        } catch (\Zip\ZipPayment\MerchantApi\Lib\ApiException $e) {

            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);

            $response['message'] = $message;
        } finally {
            $log['response'] = $response;
        }

        return $response;
    }
}
