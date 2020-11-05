<?php

namespace Zip\ZipPayment\Model\Checkout;

use \Magento\Checkout\Model\Type\Onepage;
use \Zip\ZipPayment\Model\Config;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
abstract class AbstractCheckout
{
    /**
     * @const
     */
    const STATUS_MAGENTO_AUTHORIZED = "zip_authorised";
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $_quoteRepository;
    /**
     * @var \Magento\Quote\Model\Quote
     */
    protected $_quote;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;
    /**
     * @var string
     */
    protected $_api;
    /**
     * @var \Zip\ZipPayment\Helper\Payload
     */
    protected $_payloadHelper;
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
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config
    )
    {
        $this->_customerSession = $customerSession;
        $this->_customerFactory = $customerFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_quoteRepository = $quoteRepository;
        $this->_payloadHelper = $payloadHelper;
        $this->_logger = $logger;
        $this->_helper = $helper;
        $this->_config = $config;

        // Configure API Credentials
        $apiConfig = \Zip\ZipPayment\MerchantApi\Lib\Configuration::getDefaultConfiguration();

        $apiConfig->setApiKey('Authorization', $this->_config->getMerchantPrivateKey())
            ->setApiKeyPrefix('Authorization', 'Bearer')
            ->setEnvironment($this->_config->getEnvironment())
            ->setPlatform("Magento/" . $this->_helper->getMagentoVersion() . "Zip_ZipPayment/" . $this->_helper->getExtensionVersion());
    }

    /**
     * Get checkout method
     *
     * @return string
     */
    public function getCheckoutMethod()
    {
        if ($this->_getCustomerSession()->isLoggedIn()) {
            return Onepage::METHOD_CUSTOMER;
        }
        if (!$this->_quote->getCheckoutMethod()) {
            if ($this->_checkoutHelper->isAllowedGuestCheckout($this->_quote)) {
                $this->_quote->setCheckoutMethod(Onepage::METHOD_GUEST);
            } else {
                $this->_quote->setCheckoutMethod(Onepage::METHOD_REGISTER);
            }
        }
        return $this->_quote->getCheckoutMethod();
    }

    /**
     * Return customer session object
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCustomerSession()
    {
        return $this->_customerSession;
    }

    /**
     * Returns api instance
     *
     * @return object
     */
    public function getApi()
    {
        if (null === $this->_api) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Api class has not been set.'));
        }

        return $this->_api;
    }

    /**
     * Sets api instance
     *
     * @return \Zip\ZipPayment\Model\Checkout\AbstractCheckout
     */
    public function setApi($api)
    {
        if (is_object($api)) {
            $this->_api = $api;
        } else if (is_string($api)) {
            $this->_api = new $api;
        }
        return $this;
    }

    /**
     * Returns quote object
     *
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        return $this->_quote;
    }

    /**
     * Sets quote object
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return \Zip\ZipPayment\Model\Checkout\AbstractCheckout
     */
    public function setQuote($quote)
    {
        if ($quote) {
            $this->_quote = $quote;
        }
        return $this;
    }

    /**
     * Returns order object
     *
     * @return \Magento\Sales\Model\Order  $order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Sets order object
     *
     * @param \Magento\Sales\Model\Order $order
     * @return \Zip\ZipPayment\Model\Checkout\AbstractCheckout
     */
    public function setOrder($order)
    {
        if ($order) {
            $this->_order = $order;
        }
        return $this;
    }

    /**
     * Generates uniq id
     *
     * @return string
     */
    public function genIdempotencyKey()
    {
        return uniqid();
    }

    /**
     * Return checkout session object
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckoutSession()
    {
        return $this->_checkoutSession;
    }

    /**
     * Checks if customer exists
     *
     * @return int
     */
    protected function _lookupCustomerId()
    {
        return $this->_customerFactory->create()
            ->loadByEmail($this->_quote->getCustomerEmail())
            ->getId();
    }

}
