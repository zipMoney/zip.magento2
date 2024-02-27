<?php

namespace Zip\ZipPayment\Gateway\Http\Client;

use \Zip\ZipPayment\MerchantApi\Lib\Configuration;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class AbstractTransaction
{

    protected $_encryptor;
    protected $_payloadHelper;
    protected $_logger;
    protected $_helper;
    protected $_config;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        array $data = []
    ) {
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
            ->setPlatform("Magento/" . $this->_helper->getMagentoVersion()
                . "Zip_ZipPayment/"
                . $this->_helper->getExtensionVersion());
    }
}
