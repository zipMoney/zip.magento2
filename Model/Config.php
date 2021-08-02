<?php

namespace Zip\ZipPayment\Model;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

class Config implements ConfigInterface
{
    /**
     * Method Code name in magento
     *
     * @const
     */
    const METHOD_CODE = 'zippayment';

    /**
     * ZipMoney Authorised Status
     *
     * @const
     */
    const STATUS_MAGENTO_AUTHORIZED = "zip_authorised";

    /**
     * Private Key
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_PRIVATE_KEY = 'merchant_private_key';

    /**
     * Public Key
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_PUBLIC_KEY = 'merchant_public_key';

    /**
     * API Envronment
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_ENVIRONMENT = 'environment';

    /**
     * Region
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_WIDGET_REGION = 'widget_region';

    const PAYMENT_ZIPMONEY_WIDGET_DISPLAY_MODE = 'display_widget_mode';

    /**
     * Payment Method Title
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_TITLE = 'title';

    /**
     * Product Type
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_PRODUCT = 'product';

    /**
     * Log Setting
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_LOG_SETTINGS = 'log_settings';

    /**
     * Payment Action
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_PAYMENT_ACTION = 'payment_action';

    /**
     * Incontext Checkout
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_INCONTEXT_CHECKOUT = 'incontext_checkout';

    /**
     * Minimum Order Total
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_MINIMUM_TOTAL = 'min_order_total';

    /**
     * Maximum Order Total
     *
     * @const
     */
    const PAYMENT_ZIPMONEY_MAXIMUM_TOTAL = 'max_order_total';

    /**
     * Incontext Checkout
     *
     * @const
     */
    const ADVERTS_HOMEPAGE_BANNER_ACTIVE = 'zip_advert/homepage/banner';

    /**
     * Homepage banner html element selector
     */
    const ADVERTS_HOMEPAGE_BANNER_SELECTOR= 'zip_advert/homepage/banner_selector';

    /**
     * Product Page Banner Active
     *
     * @const
     */
    const ADVERTS_PRODUCT_BANNER_ACTIVE = 'zip_advert/productpage/banner';

    /**
     * Porduct page banner html selector
     */
    const ADVERTS_PRODUCT_BANNER_SELECTOR = 'zip_advert/productpage/banner_selector';

    /**
     * Cart Page Banner Active
     *
     * @const
     */
    const ADVERTS_CART_BANNER_ACTIVE = 'zip_advert/cartpage/banner';

    /**
     * Cart Page Banner html element selector
     */
    const ADVERTS_CART_BANNER_SELECTOR = 'zip_advert/cartpage/banner_selector';

    /**
     * Category Page Banner Active
     *
     * @const
     */
    const ADVERTS_CATEGORY_BANNER_ACTIVE = 'zip_advert/categorypage/banner';

    /**
     * Category Page Banner html element selector
     */
    const ADVERTS_CATEGORY_BANNER_SELECTOR = 'zip_advert/categorypage/banner_selector';

    /**
     * Product Page Widget Active
     *
     * @const
     */
    const ADVERTS_PRODUCT_IMAGE_ACTIVE = 'zip_advert/productpage/widget';

    /**
     * Product Page Widget html element selector
     *
     * @const
     */
    const ADVERTS_PRODUCT_IMAGE_SELECTOR = 'zip_advert/productpage/widget_selector';

    /**
     * Cart Page Widget Active
     *
     * @const
     */
    const ADVERTS_CART_IMAGE_ACTIVE = 'zip_advert/cartpage/widget';

    /**
     * Cart Page Widget html element selector
     *
     * @const
     */
    const ADVERTS_CART_IMAGE_SELECTOR = 'zip_advert/cartpage/widget_selector';

    /**
     * Cart Page Tagline Active
     *
     * @const
     */
    const ADVERTS_PRODUCT_TAGLINE_ACTIVE = 'zip_advert/productpage/tagline';

    /**
     * Cart Page Tagline html element selector
     *
     * @const
     */
    const ADVERTS_PRODUCT_TAGLINE_SELECTOR = 'zip_advert/productpage/tagline_selector';

    /**
     * Cart Page Tagline Active
     *
     * @const
     */
    const ADVERTS_CART_TAGLINE_ACTIVE = 'zip_advert/cartpage/tagline';

    /**
     * Cart Page Tagline html element selector
     *
     * @const
     */
    const ADVERTS_CART_TAGLINE_SELECTOR = 'zip_advert/cartpage/tagline_selector';

    /**
     * Payment Method Logo Url
     *
     * @const
     */
    const PAYMENT_METHOD_LOGO_ZIP = "https://static.zipmoney.com.au/logo/25px/zip.png";

    /**
     * Error Codes Map
     *
     * @var array
     */
    protected $_error_codes_map = [
        "account_insufficient_funds" => "MG2-0001",
        "account_inoperative" => "MG2-0002",
        "account_locked" => "MG2-0003",
        "amount_invalid" => "MG2-0004",
        "fraud_check" => "MG2-0005"
    ];

    /**
     * @var string
     */
    protected $_methodCode;

    /**
     * @var int
     */
    protected $_storeId;

    /**
     *
     * @var object
     */
    protected $_methodInstance;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * @var \Zip\ZipPayment\Helper\Logger
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $_locale;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Magento\Framework\Locale\Resolver $locale
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_logger = $logger;
        $this->_resourceConfig = $resourceConfig;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_messageManager = $messageManager;
        $this->_urlBuilder = $urlBuilder;
        $this->_locale = $locale;

        $this->setStoreId($this->_storeManager->getStore()->getId());
    }

    /**
     * Store ID setter
     *
     * @param int $storeId
     * @return \Zip\ZipPayment\Model
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = (int)$storeId;
        return $this;
    }

    /**
     * Checks whether method is active or not
     *
     * @param $method
     * @param int $storeId
     * @return bool
     */
    public function isMethodActive($method, $storeId = null)
    {
        if (!isset($storeId)) {
            $storeId = $this->_storeId;
        }

        $isEnabled = false;
        $isEnabled = $this->_scopeConfig->isSetFlag(
            'payment/' . self::METHOD_CODE . '/active',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        return $isEnabled;
    }

    /**
     * Returns the payment method title
     *
     * @return int
     */
    public function getTitle()
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_TITLE);
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null|\Magento\Store\Model\Store $storeId
     *
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        if ('order_place_redirect_url' === $field) {
            return $this->getOrderPlaceRedirectUrl();
        }

        if (!$storeId) {
            $storeId = $this->_storeId;
        }

        return $this->getValue($field, $storeId);
    }

    /**
     * Returns payment configuration value
     *
     * @param string $key
     * @param null $storeId
     * @return null|string
     */
    public function getValue($key, $storeId = null)
    {
        if (!$storeId) {
            $storeId = $this->_storeId;
        }

        $underscored = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $key));

        $path = "payment/" . self::METHOD_CODE . "/" . $underscored;

        $value = $this->_scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $value;
    }

    /**
     * Returns Merchant Private Key
     *
     * @return int
     */
    public function getMerchantPrivateKey($storeId = null)
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_PRIVATE_KEY, $storeId);
    }

    /**
     * Returns Merchant Public key
     *
     * @return string
     */
    public function getMerchantPublicKey($storeId = null)
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_PUBLIC_KEY, $storeId);
    }

    /**
     * Returns Api Environment
     *
     * @return string
     */
    public function getEnvironment($storeId = null)
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_ENVIRONMENT, $storeId);
    }

    /**
     * Returns Default Merchant Private Key
     *
     * @return int
     */
    public function getDefaultMerchantPrivateKey()
    {
        return $this->getDefaultConfig(self::PAYMENT_ZIPMONEY_PRIVATE_KEY);
    }

    /**
     * Returns the default config
     *
     * @param $path
     * @return mixed
     */
    public function getDefaultConfig($key)
    {
        $underscored = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $key));

        $path = "payment/" . self::METHOD_CODE . "/" . $underscored;

        $value = $this->_scopeConfig->getValue(
            $path
        );

        return $value;
    }

    /**
     * Returns Default Merchant Public key
     *
     * @return string
     */
    public function getDefaultMerchantPublicKey()
    {
        return $this->getDefaultConfig(self::PAYMENT_ZIPMONEY_PUBLIC_KEY);
    }

    /**
     * Returns Default Api Environment
     *
     * @return string
     */
    public function getDefaultEnvironment()
    {
        return $this->getDefaultConfig(self::PAYMENT_ZIPMONEY_ENVIRONMENT);
    }

    /**
     * Returns Region
     *
     * @return string
     */
    public function getRegion($storeId = null)
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_WIDGET_REGION, $storeId);
    }

    /**
     * Returns Widget Display mode
     * @param null $storeId
     * @return mixed|string|null
     */
    public function getWidgetDisplayMode($storeId = null)
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_WIDGET_DISPLAY_MODE, $storeId);
    }

    /**
     * Returns method log url
     *
     * @return string
     */
    public function getMethodLogo()
    {
        return self::PAYMENT_METHOD_LOGO_ZIP;
    }

    /**
     * Check if in-context checkout is active
     *
     * @return bool
     */
    public function isInContextCheckout()
    {
        $currentCurrencyCode = $this->_storeManager->getStore()->getCurrentCurrencyCode();
        if ($currentCurrencyCode != CommonUtil::CURRENCY_AUD) {
            return false;
        }
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_INCONTEXT_CHECKOUT);
    }

    /**
     * Returns the minimum order total
     *
     * @return bool
     */
    public function getOrderTotalMinimum()
    {
        return (float)$this->getConfigData(self::PAYMENT_ZIPMONEY_MINIMUM_TOTAL);
    }

    /**
     * Returns the minimum order total
     *
     * @return bool
     */
    public function getOrderTotalMaximum()
    {
        return (float)$this->getConfigData(self::PAYMENT_ZIPMONEY_MAXIMUM_TOTAL);
    }

    /**
     * Is Capture
     *
     * @return bool
     */
    public function isCharge()
    {
        return trim($this->getConfigData(self::PAYMENT_ZIPMONEY_PAYMENT_ACTION)) === "capture";
    }

    /**
     * Returns the log setting
     *
     * @return int
     */
    public function getLogSetting($storeId = null)
    {
        return $this->getConfigData(self::PAYMENT_ZIPMONEY_LOG_SETTINGS, $storeId);
    }

    /**
     * Returns the mapped error code
     *
     * @return int
     */
    public function getMappedErrorCode($errorCode)
    {
        if (!in_array($errorCode, array_keys($this->_error_codes_map))) {
            return;
        }
        return $this->_error_codes_map[$errorCode];
    }

    /**
     * Sets method instance used for retrieving method specific data
     *
     * @param MethodInterface $method
     * @return \Zip\ZipPayment\Model
     */
    public function setMethodInstance($methodInstance)
    {
        $this->_methodInstance = $methodInstance;
        return $this;
    }

    /**
     * Sets method code
     *
     * @param string $methodCode
     * @return string
     */
    public function getMethodCode()
    {
        if (isset($this->_methodCode)) {
            return $this->_methodCode;
        }
    }

    /**
     * Sets method code
     *
     * @param string $methodCode
     * @return void
     */
    public function setMethodCode($methodCode)
    {
        $this->_methodCode = $methodCode;
    }

    /**
     * Sets path pattern
     *
     * @param string $pathPattern
     * @return void
     */
    public function setPathPattern($pathPattern)
    {
        $this->pathPattern = $pathPattern;
    }

    /**
     * Returns the logo url
     *
     * @return string
     */
    public function getPaymentAcceptanceMarkSrc()
    {
        return self::PAYMENT_METHOD_LOGO_ZIP;
    }

    /**
     * Return Redirect Url
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $url = $this->_urlBuilder->getUrl('zippayment/complete');

        return $url;
    }

    /**
     * Return Checkout Url
     *
     * @return string
     */
    public function getCheckoutUrl()
    {
        // $url = $this->_urlBuilder->getUrl('rest/default/V1/zipmoney/standard');
        $url = $this->_urlBuilder->getUrl('zippayment/standard');

        return $url;
    }

    /**
     * Returns the config
     *
     * @param $path
     * @param int $storeId
     * @return mixed
     */
    public function getStoreConfig($path, $storeId = null)
    {

        if (!isset($storeId)) {
            $storeId = $this->_storeId;
        }

        $value = $this->_scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $value;
    }

    public function getLanguageCode()
    {
        $currentLanguageCode = substr($this->_locale->getLocale(), 0, strpos($this->_locale->getLocale(), '_'));
        return $currentLanguageCode;
    }
}
