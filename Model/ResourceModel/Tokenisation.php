<?php

/**
 * @copyright 2020 Zip Co Limited
 * @link      http://zip.co
 */

namespace Zip\ZipPayment\Model\ResourceModel;

class Tokenisation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public const ZIP_CUSTOMER_TOKEN_TABLE_NAME = 'zip_customer_token';

    /**
     * Initialise the main table and its primary key
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::ZIP_CUSTOMER_TOKEN_TABLE_NAME, 'id');
    }
}
