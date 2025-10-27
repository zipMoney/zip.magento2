<?php declare(strict_types=1);

namespace Zip\ZipPayment\ViewModel;

class WidgetConfig implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    protected \Zip\ZipPayment\Model\Config $config;
    protected \Magento\Framework\Serialize\SerializerInterface $serializer;
    protected \Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider $notAllowedProductsProvider;
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;
    protected \Magento\Framework\Registry $registry;

    public function __construct(
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Zip\ZipPayment\Model\Config $config,
        \Zip\ZipPayment\Model\ResourceModel\NotAllowedProductsProvider $notAllowedProductsProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry
    ) {
        $this->serializer = $serializer;
        $this->config = $config;
        $this->notAllowedProductsProvider = $notAllowedProductsProvider;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
    }

    /**
     * Check if current product (on PDP) is allowed
     *
     * @return bool|null Returns null if not on product page, true/false otherwise
     */
    public function isCurrentProductAllowed(): ?bool
    {
        $product = $this->registry->registry('current_product');
        if (!$product || !$product->getId()) {
            return null; // Not on product page
        }

        $storeId = (int)$this->storeManager->getStore()->getId();
        $isNotAllowed = $this->notAllowedProductsProvider->isProductNotAllowed(
            (int)$product->getId(),
            $storeId
        );

        return !$isNotAllowed; // Invert: isNotAllowed -> isAllowed
    }

    /**
     * Get current product ID if on product page
     *
     * @return int|null
     */
    public function getCurrentProductId(): ?int
    {
        $product = $this->registry->registry('current_product');
        return $product && $product->getId() ? (int)$product->getId() : null;
    }

    /**
     * Get list of product IDs that are not allowed for Zip payment
     * Only used for cart/checkout pages
     *
     * @return array
     */
    public function getNotAllowedProductIds(): array
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        return $this->notAllowedProductsProvider->provideIds($storeId);
    }

    /**
     * Get lightweight config for JS (only what's needed for current page)
     *
     * @return string
     */
    public function getWidgetConfigJson(): string
    {
        $config = [
            'isActive' => $this->config->isMethodActive()
        ];

        // For product page - only send current product availability (1 boolean vs 1000+ IDs)
        $currentProductId = $this->getCurrentProductId();
        if ($currentProductId !== null) {
            $config['currentProductId'] = $currentProductId;
            $config['isCurrentProductAllowed'] = $this->isCurrentProductAllowed();
            // Don't send full array on product pages
        } else {
            // For other pages (cart, checkout) - may need full list if implementing cart-level checks
            // But usually validator handles this on server, so we can skip it
            // $config['notAllowedProducts'] = $this->getNotAllowedProductIds();
        }

        return $this->serializer->serialize($config);
    }
}
