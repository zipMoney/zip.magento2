<?php declare(strict_types=1);

namespace Zip\ZipPayment\Model\ResourceModel;

class NotAllowedProductsProvider
{
    private \Zip\ZipPayment\Model\Config $config;
    private \Magento\Framework\App\ResourceConnection $resourceConnection;

    public function __construct(
        \Zip\ZipPayment\Model\Config $config,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->config = $config;
        $this->resourceConnection = $resourceConnection;
    }

    public function provideIds(?int $storeId = null): array
    {
        $excludedCategoriesIds = $this->config->getExcludeCategories($storeId);
        if (empty($excludedCategoriesIds)) {
            return [];
        }

        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()->from(
            ['cat' => $this->resourceConnection->getTableName('catalog_category_product')],
            'cat.product_id'
        )->where($connection->prepareSqlCondition('cat.category_id', ['in' => $excludedCategoriesIds]));

        return array_map(static function ($id): int {
            return (int) $id;
        }, $connection->fetchCol($select));
    }

    /**
     * Check if a single product is not allowed based on excluded categories
     *
     * @param int $productId
     * @param int|null $storeId
     * @return bool True if product is in excluded categories (not allowed), false otherwise
     */
    public function isProductNotAllowed(int $productId, ?int $storeId = null): bool
    {
        $excludedCategoriesIds = $this->config->getExcludeCategories($storeId);
        if (empty($excludedCategoriesIds)) {
            return false;
        }

        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from(
                ['cat' => $this->resourceConnection->getTableName('catalog_category_product')],
                'COUNT(*)'
            )
            ->where('cat.product_id = ?', $productId)
            ->where($connection->prepareSqlCondition('cat.category_id', ['in' => $excludedCategoriesIds]));

        $count = (int)$connection->fetchOne($select);

        return $count > 0;
    }
}
