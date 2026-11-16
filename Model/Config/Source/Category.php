<?php declare(strict_types=1);

namespace Zip\ZipPayment\Model\Config\Source;

class Category implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private \Magento\Framework\App\RequestInterface $request;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    private \Magento\Catalog\Helper\Category $categoryHelper;

    /**
     * @var \Zip\ZipPayment\Model\Config\CategorySourceRegistry
     */
    private \Zip\ZipPayment\Model\Config\CategorySourceRegistry $categorySourceRegistry;

    /**
     * Category constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Zip\ZipPayment\Model\Config\CategorySourceRegistry $categorySourceRegistry
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Zip\ZipPayment\Model\Config\CategorySourceRegistry $categorySourceRegistry
    ) {
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->categoryHelper = $categoryHelper;
        $this->categorySourceRegistry = $categorySourceRegistry;
    }

    /**
     * Returns the category options, indented by level
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->getCategoriesTree() as $categoryData) {
            $this->renderSubCategory($categoryData, $options);
        }
        return array_reverse($options);
    }

    /**
     * Appends the category and its children to the options
     *
     * @param array $categoryData
     * @param array $optionsResult
     * @return void
     */
    private function renderSubCategory(array $categoryData, array &$optionsResult): void
    {
        if (isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $subCatData) {
                $this->renderSubCategory($subCatData, $optionsResult);
            }
        }

        if (!isset($categoryData['level']) || !is_numeric($categoryData['level']) || $categoryData['level'] < 2) {
            return;
        }

        $optionsResult[] = [
            'label' => str_repeat('―', $categoryData['level'] - 2) . $categoryData['label'],
            'value' => $categoryData['id']
        ];
    }

    /**
     * Returns the store category tree, including inactive categories
     *
     * @return array
     */
    private function getCategoriesTree(): array
    {
        $currentStoreId = $this->storeManager->getStore()->getId();

        $this->storeManager->setCurrentStore($this->getStoreIdByRequest() ?? $currentStoreId);
        $this->categorySourceRegistry->setShowAllCategories(true);
        /** @var \Magento\Framework\Data\Collection $categories */
        $categories = $this->categoryHelper->getStoreCategories(false, true);
        $this->categorySourceRegistry->setShowAllCategories(false);
        $this->storeManager->setCurrentStore($currentStoreId);

        return $this->convertToTree($categories);
    }

    /**
     * Builds a tree from the flat category collection
     *
     * @param \Magento\Framework\Data\Collection $categories
     * @return array
     */
    private function convertToTree(\Magento\Framework\Data\Collection $categories): array
    {
        $categoryById = [];
        foreach ($categories as $category) {
            foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = ['id' => $categoryId, 'children' => []];
                }
            }
            $categoryById[$category->getId()]['label'] = $category->getName();
            $categoryById[$category->getId()]['level'] = $category->getLevel();
            $categoryById[$category->getParentId()]['children'][] = & $categoryById[$category->getId()];
        }
        foreach ($categoryById as $categoryData) {
            if (!isset($categoryData['label']) && isset($categoryData['children'])) {
                return $categoryData['children'];
            }
        }
        return [];
    }

    /**
     * Returns the store id from the store or website request parameter
     *
     * @return int|null
     */
    private function getStoreIdByRequest(): ?int
    {
        if ($storeId = $this->request->getParam('store')) {
            return (int)$storeId;
        }
        if ($websiteId = $this->request->getParam('website')) {
            /** @var \Magento\Store\Model\Website $website */
            $website = $this->storeManager->getWebsite($websiteId);
            return (int)$website->getDefaultStore()->getId();
        }
        return null;
    }
}
