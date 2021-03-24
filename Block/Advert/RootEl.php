<?php

namespace Zip\ZipPayment\Block\Advert;

use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;
use Magento\Catalog\Block as CatalogBlock;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class RootEl extends AbstractAdvert implements CatalogBlock\ShortcutInterface
{
    /**
     * Get country path
     */
    const COUNTRY_CODE_PATH = 'general/country/default';

    /**
     * Get merchant public key
     *
     * @return string
     */
    public function getMerchantPublicKey()
    {
        return $this->_config->getMerchantPublicKey();
    }

    /**
     * Get API environment sandbox|live
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->_config->getEnvironment();
    }

    /**
     * get region
     * @return string
     */
    public function getRegion()
    {
        return $this->_config->getRegion();
    }

    public function getOrderTotalMinimum()
    {
        return $this->_config->getOrderTotalMinimum();
    }

    public function getOrderTotalMaximum()
    {
        return $this->_config->getOrderTotalMaximum();
    }

    /**
     * display product widget in line
     */
    public function isDisplayInlineWidget()
    {
        $displayMode = $this->_config->getWidgetDisplayMode();
        $displayInline = "false";
        if ($displayMode == CommonUtil::INLINE) {
            $displayInline = "true";
        }
        return $displayInline;
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
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {
        foreach ($this->_supportedWidgetTypes as $widgetType) {
            if ($this->_configShow($widgetType, $this->getPageType())) {
                return parent::_toHtml();
            }
        }
        return '';
    }
}
