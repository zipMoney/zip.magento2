<?php

namespace Zip\ZipPayment\Block\Advert;

use Magento\Catalog\Block as CatalogBlock;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Tagline extends AbstractAdvert implements CatalogBlock\ShortcutInterface
{
    /**
     * @const string
     */
    const WIDGET_TYPE = "tagline";

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
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {

        if ($this->_configShow(self::WIDGET_TYPE, $this->getPageType())
            && !$this->_isSelectorExist(self::WIDGET_TYPE, $this->getPageType())) {
            return parent::_toHtml();
        }
        return '';
    }
}
