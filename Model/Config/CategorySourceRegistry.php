<?php declare(strict_types=1);

namespace Zip\ZipPayment\Model\Config;

class CategorySourceRegistry
{
    /**
     * @var bool
     */
    private bool $showAllCategories = false;

    /**
     * Whether the category source should list all categories
     *
     * @return bool
     */
    public function getShowAllCategories(): bool
    {
        return $this->showAllCategories;
    }

    /**
     * Set whether the category source should list all categories
     *
     * @param bool $value
     * @return void
     */
    public function setShowAllCategories(bool $value): void
    {
        $this->showAllCategories = $value;
    }
}
