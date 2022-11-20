<?php

namespace Zip\ZipPayment\Model;

class Tokenisation extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Zip\ZipPayment\Model\ResourceModel\Tokenisation');
    }
}
