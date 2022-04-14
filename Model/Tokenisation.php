<?php

namespace Zip\ZipPayment\Model;

class Tokenisation extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    protected function _construct()
    {
        $this->_init('Zip\ZipPayment\Model\ResourceModel\Tokenisation');
    }

    public function getIdentities()
    {
        return [$this->getId()];
    }
}
