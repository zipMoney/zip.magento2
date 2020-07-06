<?php
namespace Zip\ZipPayment\Logger;
  
class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/zippayment.log';
}