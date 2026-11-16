<?php

namespace Zip\ZipPayment\Model;

use Magento\Checkout\Model\Type\Onepage;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Sales\Model\Order;
use Zip\ZipPayment\Model\Checkout\AbstractCheckout;

/**
 * @copyright 2020 Zip Co Limited
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
class Token extends AbstractCheckout
{
    /**
     * @var \Zip\ZipPayment\Model\Tokenisation
     */
    protected $_tokenisationFactory;

    /**
     * @var \Magento\Quote\Api\CartManagementInterface
     */
    protected $_quoteManagement;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $_accountManagement;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $_orderSender;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var \Magento\Sales\Api\OrderPaymentRepositoryInterface
     */
    protected $_orderPaymentRepository;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $_objectCopyService;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $_dataObjectHelper;

    /**
     * Token constructor.
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Quote\Api\CartManagementInterface $cartManagement
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Sales\Api\OrderPaymentRepositoryInterface $orderPaymentRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManagement
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Zip\ZipPayment\Helper\Payload $payloadHelper
     * @param \Zip\ZipPayment\Helper\Logger $logger
     * @param \Zip\ZipPayment\Helper\Data $helper
     * @param \Zip\ZipPayment\Model\Config $config
     * @param \zipMoney\Api\TokensApi $tokenApi
     * @param \Zip\ZipPayment\Model\TokenisationFactory $tokenFactory
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
        \zipMoney\Api\TokensApi $tokenApi,
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
     * @return \zipMoney\Model\Token
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createToken()
    {
        $payload = $this->_payloadHelper->getTokenPayload();
        try {
            $token = $this->getApi()->tokensCreate($payload, $this->genIdempotencyKey());
        } catch (\zipMoney\ApiException $e) {
            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);

            // Cancel the order
            $this->_helper->cancelOrder($this->_order, $apiError);
            throw new \Magento\Framework\Exception\LocalizedException(__($message));
        }
        return $token;
    }
}
