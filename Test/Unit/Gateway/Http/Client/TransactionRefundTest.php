<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Zip\ZipPayment\Test\Unit\Gateway\Http\Client;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Payment\Gateway\Http\TransferInterface;
use Zip\ZipPayment\Model\Config;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class TransactionRefundTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Context | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var ConfigInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    protected $messageManager;

    public function setUp()
    {

        $objManager = new ObjectManager($this);

        $config = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $config->expects(static::any())->method('getLogSetting')->willReturn(10);

        $this->_refundsApiMock = $this->getMockBuilder(\Zip\ZipPayment\MerchantApi\Lib\Api\RefundsApi::class)->getMock();

        $this->_clientMock = $objManager->getObject("\Zip\ZipPayment\Gateway\Http\Client\TransactionRefund",
            ['_service' => $this->_refundsApiMock]);

    }

    /**
     * @param array $expectedRequest
     * @param array $expectedResponse
     *
     * @dataProvider placeRequestDataProvider
     */
    public function testPlaceRequest($expectedRequest, $expectedResponse)
    {
        $transferObject = $this->getMockBuilder("\Magento\Payment\Gateway\Http\TransferInterface")->getMock();

        $transferObject->expects(static::any())->method('getBody')->willReturn($expectedRequest);
        $this->_refundsApiMock->expects(static::any())->method('refundsCreate')->willReturn($expectedResponse);

        static::assertEquals(
            ['api_response' => $expectedResponse],
            $this->_clientMock->placeRequest($transferObject)
        );
    }


    public function placeRequestDataProvider()
    {
        $chargeResponse = new \Zip\ZipPayment\MerchantApi\Lib\Model\Charge;

        $chargeResponse->setId("112343");
        $chargeResponse->setState("refunded");
        return [
            'success' => [
                'expectedRequest' => [
                    'payload' => null,
                    'zip_checkout_id' => 123
                ],
                $chargeResponse
            ]
        ];
    }

}
