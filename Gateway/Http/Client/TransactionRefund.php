<?php

namespace Zip\ZipPayment\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\ClientException;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

/**
 * Class TransactionCapture
 */
class TransactionRefund extends AbstractTransaction implements ClientInterface
{
    protected $_service = null;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        \Zip\ZipPayment\MerchantApi\Lib\Api\RefundsApi $refundsApi,
        array $data = []
    )
    {
        parent::__construct($context, $encryptor, $payloadHelper, $logger, $helper, $config);
        $this->_service = $refundsApi;
    }

    /**
     * @param \Magento\Payment\Gateway\Http\TransferInterface $transferObject
     * @return array
     */
    public function placeRequest(\Magento\Payment\Gateway\Http\TransferInterface $transferObject)
    {
        $request = $transferObject->getBody();
        $payload = $request['payload'];
        $storeId = isset($request['store_id']) ? $request['store_id'] : null;
        // reset API depend on payload
        $this->_logger->info("refund store id: " . $storeId);
        $apiConfig = new \Zip\ZipPayment\MerchantApi\Lib\Configuration();
        $apiConfig->setApiKey('Authorization', $this->_config->getMerchantPrivateKey($storeId))
            ->setApiKeyPrefix('Authorization', 'Bearer')
            ->setEnvironment($this->_config->getEnvironment($storeId))
            ->setPlatform(
                "Magento/"
                . $this->_helper->getMagentoVersion()
                . "Zip_ZipPayment/"
                . $this->_helper->getExtensionVersion()
            );
        $this->_service->setApiClient(new \Zip\ZipPayment\MerchantApi\Lib\ApiClient($apiConfig));

        $response = null;

        try {
            $refund = $this->_service->refundsCreate($payload, $this->_helper->generateIdempotencyKey());
            $response = ["api_response" => $refund];
            $this->_logger->debug("Refund Response:- " . $this->_helper->json_encode($refund));
        } catch (\Zip\ZipPayment\MerchantApi\Lib\ApiException $e) {
            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);
            $response['message'] = $message;
        } finally {
            $log['response'] = $response;
        }

        return $response;
    }
}
