<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zip\ZipPayment\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://www.zip.co
 */
abstract class AbstractDataBuilder implements BuilderInterface
{
    /**
     * @var \Zip\ZipPayment\Helper\Payload
     */
    protected $_payloadHelper;
    /**
     * @var \Zip\ZipPayment\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    public function __construct(
        \Magento\Sales\Model\Order $order,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Helper\Logger $logger
    ) {
        $this->_payloadHelper = $payloadHelper;
        $this->_helper = $helper;
        $this->_logger = $logger;
        $this->_order = $order;
    }
}
