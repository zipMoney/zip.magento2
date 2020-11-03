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
class TransactionCancel extends AbstractTransaction implements ClientInterface
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
    )
    {

        parent::__construct($context, $encryptor, $payloadHelper, $logger, $helper, $config);

        $this->_service = $chargesApi;

    }

    /**
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
            $this->_logger->debug("Cancel Response:- " . $this->_helper->json_encode($cancel));

        } catch (\Zip\ZipPayment\MerchantApi\Lib\ApiException $e) {
            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);

            $response['message'] = $message;
        } finally {
            $log['response'] = $response;
        }

        return $response;
    }

}
