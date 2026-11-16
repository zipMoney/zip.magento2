<?php

namespace Zip\ZipPayment\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/zippayment.log';
}
