<?php

namespace Zip\ZipPayment\Model;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Sales\Model\Order;
use Zip\ZipPayment\Model\Checkout\AbstractCheckout;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class Token extends AbstractCheckout
{
    /**
     * @var \Zip\ZipPayment\Model\TokenisationFactory
     */

    protected $_tokenisationFactory;
    /**
     * Set quote and config instances
     *
     * @param array $params
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Api\CartManagementInterface $cartManagement,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Api\OrderPaymentRepositoryInterface $orderPaymentRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Zip\ZipPayment\Helper\Payload $payloadHelper,
        \Zip\ZipPayment\Helper\Logger $logger,
        \Zip\ZipPayment\Helper\Data $helper,
        \Zip\ZipPayment\Model\Config $config,
        \Zip\ZipPayment\MerchantApi\Lib\Api\TokensApi $tokenApi,
        \Zip\ZipPayment\Model\TokenisationFactory $tokenFactory
    ) {
        $this->_quoteManagement = $cartManagement;
        $this->_accountManagement = $accountManagement;
        $this->_messageManager = $messageManager;
        $this->_customerRepository = $customerRepository;
        $this->_customerUrl = $customerUrl;
        $this->_orderSender = $orderSender;
        $this->_orderRepository = $orderRepository;
        $this->_orderPaymentRepository = $orderPaymentRepository;
        $this->_objectCopyService = $objectCopyService;
        $this->_dataObjectHelper = $dataObjectHelper;
        $this->_api = $tokenApi;
        $this->_tokenisationFactory = $tokenFactory->create();

        parent::__construct(
            $customerSession,
            $checkoutSession,
            $customerFactory,
            $quoteRepository,
            $payloadHelper,
            $logger,
            $helper,
            $config
        );
    }

    /**
     * Charges the customer against the order
     *
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Token
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createToken()
    {
        $payload = $this->_payloadHelper->getTokenPayload();
        try {
            $token = $this->getApi()->tokensCreate($payload, $this->genIdempotencyKey());
        } catch (\Zip\ZipPayment\MerchantApi\Lib\ApiException $e) {
            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);

            // Cancel the order
            $this->_helper->cancelOrder($this->_order, $apiError);
            throw new \Magento\Framework\Exception\LocalizedException(__($message));
        }
        return $token;
    }
}
