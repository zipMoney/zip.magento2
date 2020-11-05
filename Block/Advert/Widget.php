<?php

namespace Zip\ZipPayment\Block\Advert;

use Magento\Catalog\Block as CatalogBlock;

class Widget extends AbstractAdvert implements CatalogBlock\ShortcutInterface
{
    /**
     * @const string
     */
    const ADVERT_TYPE = "widget";

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
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {

        if ($this->_configShow(self::ADVERT_TYPE, $this->getPageType())) {
            return parent::_toHtml();
        }

        return '';
    }
}
