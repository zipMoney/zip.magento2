<?php

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      http://zip.co
 */

namespace Zip\ZipPayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Model\Session;
use Magento\Payment\Helper\Data as PaymentHelper;
use Zip\ZipPayment\Model\Config;
use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @const string
     */
    const CODE = 'zippayment';

    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Magento\Payment\Model\Method\AbstractMethod[]
     */
    protected $methods = [];

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     *
     * @var \Zip\ZipPayment\Helper\Logger
     */
    protected $_logger;

    /**
     * @var \Magento\Customer\Model\Session
     */

    protected $_customerSession;

    /**
     * @var \Zip\ZipPayment\Model\TokenisationFactory
     */
    protected $_tokenisationFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;


    /**
     * @param ResolverInterface $localeResolver
     * @param CurrentCustomer $currentCustomer
     * @param PaymentHelper $paymentHelper
     * @param Session $customerSession
     * @param \Zip\ZipPayment\Model\TokenisationFactory $tokenFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        ResolverInterface $localeResolver,
        CurrentCustomer $currentCustomer,
        PaymentHelper $paymentHelper,
        Config $config,
        \Zip\ZipPayment\Helper\Logger $logger,
        Session $customerSession,
        \Zip\ZipPayment\Model\TokenisationFactory $tokenFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->localeResolver = $localeResolver;
        $this->currentCustomer = $currentCustomer;
        $this->paymentHelper = $paymentHelper;
        $this->_config = $config;
        $this->_logger = $logger;
        $this->_customerSession = $customerSession;
        $this->_tokenisationFactory = $tokenFactory->create();
        $this->_storeManager = $storeManager;
    }

    /**
     * Prepares the Js Config
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $paymentAcceptanceMarkSrc = $this->_config->getPaymentAcceptanceMarkSrc(self::CODE);
        $config['payment'][self::CODE] = [
            "code" => self::CODE,
            "paymentAcceptanceMarkSrc" => $paymentAcceptanceMarkSrc,
            "checkoutUri" => $this->_config->getCheckoutUrl(),
            "redirectUri" => $this->_config->getRedirectUrl(),
            "environment" => $this->_config->getEnvironment(),
            "title" => $this->_config->getTitle(),
            "iframe" => false,
            "isTokenisationEnabled" => $this->_canCustomerSeeTokenisationOption(),
            "isCustomerWantTokenisation" => $this->_isCustomerSelectedTokenisationBefore(),
            "isRedirect" => $this->_isRedirect(),
        ];
        return $config;
    }

    /**
     * check database customer already has token
     */
    protected function _isCustomerSelectedTokenisationBefore()
    {
        $currentCurrencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        if ($currentCurrencyCode != CommonUtil::CURRENCY_AUD) {
            return false;
        }
        if ($this->_customerSession->isLoggedIn()) {
            $this->_tokenisationFactory->load($this->_customerSession->getCustomerId(), 'customer_id');
            if ($this->_tokenisationFactory->getCustomerToken()) {
                return true;
            }
        }
        return false;
    }

    protected function _canCustomerSeeTokenisationOption()
    {
        return $this->_config->isTokenisationEnabled() && $this->_isCustomerLoggedin();
    }

    protected function _isCustomerLoggedin()
    {
        if ($this->_customerSession->isLoggedIn()) {
            return true;
        }
        return false;
    }

    protected function _isRedirect()
    {
        return $this->_canCustomerSeeTokenisationOption() && $this->_isCustomerSelectedTokenisationBefore();
    }
}
