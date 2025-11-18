<?php
namespace Zip\ZipPayment\Block\Advert;

use Magento\Catalog\Block as CatalogBlock;
use Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider;
use Magento\Checkout\Model\Session as CheckoutSession;

class Widget extends AbstractAdvert implements CatalogBlock\ShortcutInterface
{
    /**
     * @const string
     */
    const ADVERT_TYPE = "widget";

    /**
     * @var NotAllowedProductsProvider
     */
    private $notAllowedProductsProvider;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Zip\ZipPayment\Model\Config $config,
        \Magento\Framework\Registry $registry,
        \Zip\ZipPayment\Helper\Logger $logger,
        CheckoutSession $checkoutSession,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        NotAllowedProductsProvider $notAllowedProductsProvider,
        array $data = []
    ) {
        parent::__construct($context, $config, $registry, $logger, $checkoutSession, $priceCurrency, $data);
        $this->notAllowedProductsProvider = $notAllowedProductsProvider;
        $this->checkoutSession = $checkoutSession;
    }

    public function getPrice()
    {
        $price = 0;
        if ($this->getPageType() == 'cart') {
            $price = $this->getCartTotal();
        }
        if ($this->getPageType() == 'product') {
            $price = $this->getProductPrice();
        }

        return $this->getCurrencyFormat($price);
    }

    /**
     * Get shortcut alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * Check if current product belongs to not allowed categories
     *
     * @return bool
     */
    private function isProductNotAllowed(): bool
    {
        $product = $this->_registry->registry('current_product');
        if (!$product) {
            return false;
        }

        return $this->notAllowedProductsProvider->isProductNotAllowed((int)$product->getId());
    }

    /**
     * Check if cart contains not allowed products
     *
     * @return bool
     */
    private function isCartHasNotAllowedProducts(): bool
    {
        $quote = $this->checkoutSession->getQuote();
        if (!$quote || !$quote->getId()) {
            return false;
        }

        $notAllowedProductIds = $this->notAllowedProductsProvider->provideIds();

        foreach ($quote->getAllVisibleItems() as $item) {
            $productId = (int)$item->getProduct()->getId();
            if (in_array($productId, $notAllowedProductIds, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {
        // Check for not allowed products based on page type
        if ($this->getPageType() == 'product' && $this->isProductNotAllowed()) {
            return '';
        }

        if ($this->getPageType() == 'cart' && $this->isCartHasNotAllowedProducts()) {
            return '';
        }

        if ($this->_configShow(self::ADVERT_TYPE, $this->getPageType())
            && !$this->_isSelectorExist(self::ADVERT_TYPE, $this->getPageType())) {
            return parent::_toHtml();
        }

        return '';
    }
}
