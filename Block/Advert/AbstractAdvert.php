<?php

namespace Zip\ZipPayment\Block\Advert;

use Zip\ZipPayment\Model\Config;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use \Magento\Framework\Currency;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
abstract class AbstractAdvert extends \Magento\Framework\View\Element\Template
{
    /**
     * @var boolean
     */
    protected $_render = false;

    /**
     * @var \Zip\ZipPayment\Model\Config
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var string
     */
    protected $_alias = '';

    /**
     * @var array
     */
    protected $_supportedWidgetTypes = ['widget', 'banner'];

    /**
     * @var array
     */
    protected $_configConstants = [
        'widget' => [
            'product' => Config::ADVERTS_PRODUCT_IMAGE_ACTIVE,
            'product_selector' => Config::ADVERTS_PRODUCT_IMAGE_SELECTOR,
            'cart' => Config::ADVERTS_CART_IMAGE_ACTIVE,
            'cart_selector' => Config::ADVERTS_CART_IMAGE_SELECTOR
        ],
        'banner' => [
            'product' => Config::ADVERTS_PRODUCT_BANNER_ACTIVE,
            'product_selector' => Config::ADVERTS_PRODUCT_BANNER_SELECTOR,
            'cart' => Config::ADVERTS_CART_BANNER_ACTIVE,
            'cart_selector' => Config::ADVERTS_CART_BANNER_SELECTOR,
            'home' => Config::ADVERTS_HOMEPAGE_BANNER_ACTIVE,
            'home_selector' => Config::ADVERTS_HOMEPAGE_BANNER_SELECTOR,
            'category' => Config::ADVERTS_CATEGORY_BANNER_ACTIVE,
            'category_selector' => Config::ADVERTS_CATEGORY_BANNER_SELECTOR
        ]
    ];

    /**
     * AbstractAdvert constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Zip\ZipPayment\Model\Config $config
     * @param \Magento\Framework\Registry $registry
     * @param \Zip\ZipPayment\Helper\Logger $logger
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Zip\ZipPayment\Model\Config $config,
        \Magento\Framework\Registry $registry,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Magento\Checkout\Model\Session $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_config = $config;
        $this->_registry = $registry;
        $this->_logger = $logger;
        $this->_checkoutSession = $checkoutSession;
        $this->_priceCurrency = $priceCurrency;
    }

    /**
     * Returns the final price of the current product
     *
     * @return float
     */
    public function getProductPrice()
    {
        $product = $this->_registry->registry('current_product');
        $price = $product->getPriceInfo()->getPrice('final_price')->getValue();
        return $price;
    }

    /**
     * Returns the quote grand total
     *
     * @return float|int
     */
    public function getCartTotal()
    {
        $totals = $this->_checkoutSession->getQuote()->getTotals();
        $totalAmount = 0;
        if (isset($totals['grand_total'])) {
            $totalAmount = $totals['grand_total']->getValueInclTax() ?: $totals['grand_total']->getValue();
        }
        return $totalAmount;
    }

    /**
     * Formats the price without the currency symbol
     *
     * @param float|int $price
     * @return string
     */
    public function getCurrencyFormat($price)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $currency = $objectManager->get(\Magento\Directory\Model\Currency::class);
        return $currency->format($price, ['display' => Currency::NO_SYMBOL], false);
    }

    /**
     * Returns the current currency symbol
     *
     * @return string
     */
    public function getCurrencySymbol()
    {
        return $this->_priceCurrency->getCurrencySymbol();
    }

    /**
     * Check if widget has been enabled
     *
     * @param string $widget
     * @param string $page
     * @return mixed
     */
    protected function _configShow($widget, $page)
    {
        if ($this->_config->isMethodActive()) {
            $configPath = $this->_getConfigPath($widget, $page);
            return $this->_config->getConfigData($configPath);
        }
        return false;
    }

    /**
     * Check if widget html selector has value
     *
     * @param string $widget
     * @param string $page
     * @return bool
     */
    protected function _isSelectorExist($widget, $page)
    {
        $selectorConfigPath = $this->_getConfigPath($widget, $page . '_selector');
        return empty($this->_config->getValue($selectorConfigPath)) ? false : true ;
    }

    /**
     * Returns the config path
     *
     * @param string $widget
     * @param string $page
     * @return string|null
     */
    protected function _getConfigPath($widget, $page)
    {
        if ($widget && $page) {
            return isset($this->_configConstants[$widget][$page]) ? $this->_configConstants[$widget][$page] : null;
        } else {
            return null;
        }
    }

    /**
     * Get element selectors for current widgets
     *
     * @return array
     */
    public function getElementSelectors()
    {
        $selectors = [];

        foreach ($this->_supportedWidgetTypes as $widgetType) {
            $pageType = $this->getPageType();
            $enabled = $this->_configShow($widgetType, $pageType);

            if ($enabled !== null && $enabled) {
                $configPath = $this->_getConfigPath($widgetType, $pageType . '_selector') ;
                $widgetType = $widgetType == 'widget' ? $pageType . '_' . $widgetType : $widgetType;
                $selectors[$widgetType] = $this->_config->getValue($configPath);
            }
        }

        return $selectors;
    }
}
