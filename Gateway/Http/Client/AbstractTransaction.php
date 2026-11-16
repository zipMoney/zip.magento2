<?php

namespace Zip\ZipPayment\Gateway\Http\Client;

use \zipMoney\Configuration;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class AbstractTransaction
{
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @var \Zip\ZipPayment\Helper\Payload
     */
    protected $_payloadHelper;

    /**
     * @var \Zip\ZipPayment\Helper\Logger
     */
    protected $_logger;

    /**
     * @var \Zip\ZipPayment\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Zip\ZipPayment\Model\Config
     */
    protected $_config;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Zip\ZipPayment\Helper\Payload $payloadHelper
     * @param \Zip\ZipPayment\Helper\Logger $logger
     * @param \Zip\ZipPayment\Helper\Data $helper
     * @param \Zip\ZipPayment\Model\Config $config
     * @param array $data
     */
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
