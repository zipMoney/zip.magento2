<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace  Zip\ZipPayment\Test\Unit\Gateway\Http\Client;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Framework\Message\Manager;
use Magento\Framework\App\RequestInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Framework\Message\ManagerInterface;
use Zip\ZipPayment\Model\Charge;
use Zip\ZipPayment\Model\Config;
use Zip\ZipPayment\Helper\Payload;
use Zip\ZipPayment\Helper\Logger;
use Zip\ZipPayment\Helper\Data as ZipMoneyDataHelper;

/**
 * @category  Zipmoney
 * @package   Zipmoney_ZipPayment
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

class TransactionCancelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Context | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var ConfigInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    public function setUp()
    {
       
        $objManager = new ObjectManager($this);
        
        $config = $this->getMockBuilder(Config::class)
                    ->disableOriginalConstructor()
                    ->getMock();

        $config->expects(static::any())->method('getLogSetting')->willReturn(10);  
        
        $this->_chargesApiMock = $this->getMockBuilder(\Zip\ZipPayment\MerchantApi\Lib\Api\ChargesApi::class)->getMock();
        
        $this->_clientMock = $objManager->getObject("\Zip\ZipPayment\Gateway\Http\Client\TransactionCancel",
            [ '_service' => $this->_chargesApiMock]);
        
    }
    /**
     * @param array $expectedRequest
     * @param array $expectedResponse
     *
     * @dataProvider placeRequestDataProvider
     */
    public function testPlaceRequest( $expectedRequest, $expectedResponse)
    {          

        $transferObject = $this->getMockBuilder("\Magento\Payment\Gateway\Http\TransferInterface")->getMock();

        $transferObject->expects(static::any())->method('getBody')->willReturn($expectedRequest);
        $this->_chargesApiMock->expects(static::any())->method('chargesCancel')->willReturn( $expectedResponse   );

        static::assertEquals(
            [ 'api_response' => $expectedResponse ],
            $this->_clientMock->placeRequest($transferObject)
        );
    }

    public function placeRequestDataProvider()
    {   
        $chargeResponse = new \Zip\ZipPayment\MerchantApi\Lib\Model\Charge;
      
        $chargeResponse->setId("112343");
        $chargeResponse->setState("cancelled");
        return [
            'success' => [
                'expectedRequest' => [
                    'zip_checkout_id' => 123
                ],
                $chargeResponse
            ]
        ];
    }

}   