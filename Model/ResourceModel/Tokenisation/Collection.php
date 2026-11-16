<?php

/**
 * @copyright 2020 Zip Co Limited
 * @link      http://zip.co
 */


namespace Zip\ZipPayment\Model\ResourceModel\Tokenisation;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public const ZIP_CUSTOMER_TOKEN_TABLE_NAME = 'zip_customer_token';

    /**
     * Initialise the model and resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            \Zip\ZipPayment\Model\Tokenisation::class,
            \Zip\ZipPayment\Model\ResourceModel\Tokenisation::class
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
