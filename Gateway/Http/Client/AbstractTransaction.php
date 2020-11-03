<?php

namespace Zip\ZipPayment\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use \Zip\ZipPayment\MerchantApi\Lib\Configuration;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class AbstractTransaction
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        array $data = []
    )
    {
        $this->_encryptor = $encryptor;
        $this->_payloadHelper = $payloadHelper;
        $this->_logger = $logger;
        $this->_helper = $helper;
        $this->_config = $config;

        // Configure API Credentials
        $apiConfig = Configuration::getDefaultConfiguration();

        $apiConfig->setApiKey('Authorization', $this->_config->getMerchantPrivateKey())
            ->setApiKeyPrefix('Authorization', 'Bearer')
            ->setEnvironment($this->_config->getEnvironment())
            ->setPlatform("Magento/" . $this->_helper->getMagentoVersion() . "Zip_ZipPayment/" . $this->_helper->getExtensionVersion());
    }
}
