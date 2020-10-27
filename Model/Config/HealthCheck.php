<?php

namespace Zip\ZipPayment\Model\Config;

use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\HTTP\Adapter\CurlFactory;
/**
 * Admin Model of health check
 *
 * @package Zip_Payment
 * @author  Zip Co - Plugin Team
 **/

class HealthCheck
{

    const STATUS_SUCCESS = 1;
    const STATUS_WARNING = 2;
    const STATUS_ERROR = 3;
    const STATUS_OK = 0;

    const SSL_DISABLED_MESSAGE = 'Your store {store_name} ({store_url}) does not have SSL';
    const CURL_EXTENSION_DISABLED = 'CURL extension has not been installed or disabled';
    const API_CERTIFICATE_INVALID_MESSAGE = 'SSL Certificate is not valid for the API';
    const API_PRIVATE_KEY_INVALID_MESSAGE = 'Your API private key is empty or invalid';
    const API_PUBLIC_KEY_INVALID_MESSAGE = 'Your API public key is empty or invalid';
    const API_CREDENTIAL_INVALID_MESSAGE = 'Your API credential is invalid';
    const MERCHANT_COUNTRY_NOT_SUPPORTED_MESSAGE = 'Your merchant country not been supported';

    protected $_result = array(
        'overall_status' => self::STATUS_SUCCESS,
        'items' => array()
    );

    protected $_region = array(
        'au' => 'Australia',
        'nz' => 'New Zealand',
        'us' => 'United States',
        'uk' => 'United Kingdom',
        'za' => 'South Africa',
    );

    /**
     * @var \Zip\ZipPayment\Helper\Logger
     */
    protected $_logger;

    /**
     * @var \Zip\ZipPayment\Model\Config
     */
    protected $_config;
    /**
     * @var \Zip\ZipPayment\Helper\Data
     */
    protected $_helper;

    /**
     * @var CurlFactory
     */
    private $_curlFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * HealthCheck constructor.
     * @param \Zip\ZipPayment\Helper\Logger $logger
     * @param \Zip\ZipPayment\Helper\Data $helper
     * @param \Zip\ZipPayment\Model\Config $config
     * @param CurlFactory $curlFactory,
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        CurlFactory $curlFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_logger  = $logger;
        $this->_helper  = $helper;
        $this->_config  = $config;
        $this->_curlFactory = $curlFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * check multiple items and get health result
     */
    public function getHealthResult()
    {
        /** @var Curl $curlObject */
        $curlObject = $this->_curlFactory->create();
        // Configure API Credentials
        $apiConfig = \Zip\ZipPayment\MerchantApi\Lib\Configuration::getDefaultConfiguration();

        $apiConfig->setApiKey('Authorization', $this->_config->getMerchantPrivateKey())
            ->setApiKeyPrefix('Authorization', 'Bearer')
            ->setEnvironment($this->_config->getEnvironment(),$this->_config->getAPiSource())
            ->setPlatform("Magento/".$this->_helper->getMagentoVersion()."Zip_ZipPayment/".$this->_helper->getExtensionVersion());


        $curlEnabled = function_exists('curl_version');
        $publicKey = $this->_config->getMerchantPublicKey();
        $privateKey = $this->_config->getMerchantPrivateKey();

        // check if private key is empty
        if (empty($privateKey)) {
            $this->appendItem(self::STATUS_ERROR, self::API_PRIVATE_KEY_INVALID_MESSAGE);
        }

        // check if public key is empty
        if (empty($publicKey)) {
            $this->appendItem(self::STATUS_ERROR, self::API_PUBLIC_KEY_INVALID_MESSAGE);
        }

        // check whether SSL is enabled
        $this->checkStoreSSLSettings();

        // check whether CURL is enabled ot not
        if (!$curlEnabled) {
            $this->appendItem(self::STATUS_ERROR, self::CURL_EXTENSION_DISABLED);
        } else {
            $curlObject->setConfig(
                array(
                    'timeout' => 10,
                )
            );

            try {
                $apiConfig->setCurlTimeout(30);
                $headers = array(
                    'Authorization: ' .
                    $apiConfig->getApiKeyPrefix('Authorization') .
                    ' ' .
                    $apiConfig->getApiKey('Authorization'),
                    'Accept : application/json',
                    'Zip-Version: 2017-03-01',
                    'Content-Type: application/json',
                    'Idempotency-Key: ' .uniqid()
                );
                $url = $apiConfig->getHost().'/me';
                $isAuEndpoint = false;

                // check api key length if it is more than or equal 50 then call SMI merchant info endpoint
                // otherwise call checkout get api endpoint only for Australia
                if (strlen($privateKey) <= 50) {
                    $checkoutId = 'au-co_PxSeQfLlpaYn6bLMZSMv13';
                    $url = $apiConfig->getHost().'/checkouts/'.$checkoutId;
                    $isAuEndpoint = true;
                }
                $curlObject->write(\Zend_Http_Client::GET, $url, '1.1', $headers);
                $response = $curlObject->read();
                $sslVerified = $curlObject->getInfo(CURLINFO_SSL_VERIFYRESULT) == 0;
                $httpCode = $curlObject->getInfo(CURLINFO_HTTP_CODE);
                // if API certification invalid
                if (!$sslVerified) {
                    $this->appendItem(self::STATUS_WARNING, self::API_CERTIFICATE_INVALID_MESSAGE);
                }

                // if API credential is invalid
                if ($httpCode == '401') {
                    $this->appendItem(self::STATUS_ERROR, self::API_CREDENTIAL_INVALID_MESSAGE);
                }
                if ($httpCode == '200' && $isAuEndpoint == false) {
                    $result = preg_split('/^\r?$/m', $response, 2);
                    $result = trim($result[1]);
                    $data = json_decode($result);
                    $this->appendItem( self::STATUS_OK, "Api key is valid for ".$data->name);
                    $regions = $data->regions;
                    if ($regions) {
                        $regionList = 'Api is valid for below regions:<br>';
                        foreach ($regions as $region) {
                            $regionList .= $this->_region[$region].'<br>';
                        }
                        $this->appendItem(self::STATUS_OK, $regionList);
                    }
                }

                if ($httpCode == '404' || $httpCode =='200' && $isAuEndpoint == true){
                    $this->appendItem( self::STATUS_OK, "Api key is valid for Australia region.");
                }
            }
            catch(\Exception $e) {
                $this->appendItem(self::STATUS_ERROR, "Error occurred, Please try again.");
            }

            $curlObject->close();
        }

        usort(
            $this->_result['items'], function ($a, $b) {
                return $b['status'] - $a['status'];
            }
        );

        return $this->_result;

    }

    protected function checkStoreSSLSettings()
    {
        $groups = $this->_storeManager->getWebsite()->getGroups();
        foreach ($groups as $key => $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    if ($store->getIsActive() !== '1'
                        || $this->_config->isMethodActive($store->getStoreId()) !== true
                    ) {
                        continue;
                    }

                    $storeSecureUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB,true);
                    $url = parse_url($storeSecureUrl);

                    if ($url['scheme'] !== 'https') {
                        $message = self::SSL_DISABLED_MESSAGE;
                        $message = str_replace('{store_name}', $store->getName(), $message);
                        $message = str_replace('{store_url}', $storeSecureUrl, $message);

                        $this->appendItem(
                            self::STATUS_WARNING,
                            $message
                        );
                    }
                }
            }
    }


    /**
     * append success and failed item into health result
     */
    protected function appendItem($status, $label)
    {
        if ($status !== null && $this->_result['overall_status'] < $status) {
            $this->_result['overall_status'] = $status;
        }

        $this->_result['items'][] = array(
            "status" => $status,
            "label" => $label
        );

    }

}
