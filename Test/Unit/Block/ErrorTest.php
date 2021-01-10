<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zip\ZipPayment\Test\Unit\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Framework\Message\Manager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Zip\ZipPayment\Block\Error;
use Zip\ZipPayment\Model\Config;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class ErrorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Context | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var ConfigInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    /**
     * @var InfoInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $paymentInfoModel;

    /**
     * @var Manager
     */
    protected $messageManager;

    public function setUp()
    {
        $this->context = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageManager = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetBodyTextReturnsErrorText()
    {
        $this->messageManager->expects(static::any())
            ->method('hasMessages')
            ->willReturn(false);

        $info = new Error(
            $this->context,
            $this->messageManager,
            $this->config
        );

        static::assertSame(
            "There was an error processing your request. Please try again later.",
            (string)$info->getBodyText()
        );
    }

    public function testGetBodyTextReturnsNull()
    {
        $this->messageManager->expects(static::any())
            ->method('hasMessages')
            ->willReturn(true);

        $info = new Error(
            $this->context,
            $this->messageManager,
            $this->config
        );

        static::assertNull($info->getBodyText());
    }

    public function testGetErrorTypeTextReturnsNull()
    {
        $this->messageManager->expects(static::any())
            ->method('hasMessages')
            ->willReturn(true);

        $info = new Error(
            $this->context,
            $this->messageManager,
            $this->config
        );

        static::assertNull($info->getBodyText());
    }
}
