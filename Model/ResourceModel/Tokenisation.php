<?php

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      http://zip.co
 */

namespace Zip\ZipPayment\Model\ResourceModel;

class Tokenisation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    const ZIP_CUSTOMER_TOKEN_TABLE_NAME = 'zip_customer_token';

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init(self::ZIP_CUSTOMER_TOKEN_TABLE_NAME, 'id');
    }
}
