<?php

namespace Zip\ZipPayment\Model;

class Tokenisation extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialise the resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Zip\ZipPayment\Model\ResourceModel\Tokenisation::class);
    }
}
