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
class Charge extends AbstractCheckout
{
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
     * @var \Magento\Customer\Model\Url
     */
    protected $_customerUrl;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepository;

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

    protected $_refundApi = null;

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
        \Zip\ZipPayment\MerchantApi\Lib\Api\ChargesApi $chargesApi,
        \Zip\ZipPayment\MerchantApi\Lib\Api\RefundsApi $refundsApi,
        array $data = []
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
        $this->_api = $chargesApi;
        $this->_refundApi = $refundsApi;

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

        if (isset($data['order'])) {
            if ($data['order'] instanceof \Magento\Sales\Model\Order) {
                $this->setQuote($data['order']);
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(__('Order instance is required.'));
            }
        }
    }

    /**
     * Charges the customer against the order
     *
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function charge()
    {
        if (!$this->_order || !$this->_order->getId()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The order does not exist.'));
        }

        $payload = $this->_payloadHelper->getChargePayload($this->_order);

        $this->_logger->debug("Charge Payload:- " . $this->_helper->jsonEncode($payload));

        try {
            $charge = $this->getApi()
                ->chargesCreate($payload, $this->genIdempotencyKey());

            $this->_logger->debug("Charge Response:- " . $this->_helper->jsonEncode($charge));

            if (isset($charge->error)) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Could not create the charge'));
            }

            if (!$charge->getState() || !$charge->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Invalid Charge'));
            }

            $this->_logger->debug($this->_helper->__("Charge State:- %s", $charge->getState()));

            if ($charge->getId()) {
                $additionalPaymentInfo = $this->_order->getPayment()->getAdditionalInformation();
                $additionalPaymentInfo['receipt_number'] = $charge->getReceiptNumber();
                $additionalPaymentInfo['zip_charge_id'] = $charge->getId();
                $payment = $this->_order->getPayment()
                    ->setAdditionalInformation($additionalPaymentInfo);
                $this->_orderPaymentRepository->save($payment);
            }

            // checking charge amount is same as order grand total
            if ($charge->getAmount() != $this->_order->getGrandTotal()) {
                $messageWithAmount = $this->_helper->__("Charge Amount is not same as Order amount. Charge amount:- %s and order amount :-%s", $charge->getAmount(),$this->_order->getGrandTotal());
                $this->_logger->debug($messageWithAmount);
                $this->_helper->cancelOrder($this->_order, $messageWithAmount);
                $apiConfig = new \Zip\ZipPayment\MerchantApi\Lib\Configuration();
                $apiConfig->setApiKey('Authorization', $this->_config->getMerchantPrivateKey())
                    ->setApiKeyPrefix('Authorization', 'Bearer')
                    ->setEnvironment($this->_config->getEnvironment())
                    ->setPlatform("Magento/" . $this->_helper->getMagentoVersion()
                        . "Zip_ZipPayment/"
                        . $this->_helper->getExtensionVersion());
                $payload = $this->_payloadHelper->getRefundPayload(
                    $this->_order,
                    $charge->getAmount(),
                    $messageWithAmount
                );
                // doing refund if the amount is captured by the charge api
                $this->_refundApi->setApiClient(new \Zip\ZipPayment\MerchantApi\Lib\ApiClient($apiConfig));
                $refund = $this->_refundApi->refundsCreate($payload, $this->_helper->generateIdempotencyKey());
                $this->_logger->debug("Refund Response:- " . $this->_helper->jsonEncode($refund));
                throw new \Magento\Framework\Exception\LocalizedException(__('Could not process the payment.'));
            }

            $this->_chargeResponse($charge, $this->_config->isCharge());
        } catch (\Zip\ZipPayment\MerchantApi\Lib\ApiException $e) {
            list($apiError, $message, $logMessage) = $this->_helper->handleException($e);

            // Cancel the order
            $this->_helper->cancelOrder($this->_order, $apiError);
            throw new \Magento\Framework\Exception\LocalizedException(__($message));
        }
        return $charge;
    }

    /**
     * Handles the charge response and captures/authorises the charge based on state
     *
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\Charge $charge
     * @param bool $isAuthAndCapture
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     */
    protected function _chargeResponse($charge, $isAuthAndCapture)
    {
        switch ($charge->getState()) {
            case 'captured':
            case 'approved':
            case 'authorised':
                /*
                 * Capture Payment
                 */
                if ($this->_config->isCharge()) {
                    $this->_capture($charge->getId(), false);
                } else {
                    $this->_authorise($charge->getId());
                }
                break;
            default:
                # code...
                break;
        }

        return $charge;
    }

    /**
     * Captures the charge
     *
     * @param string $txnId
     * @param bool $isAuthAndCapture
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _capture($txnId, $isAuthAndCapture = false)
    {
        /* If the capture has a corresponding authorisation before
         * authorise -> capture
         */
        if ($isAuthAndCapture) {

            // Check if order has valid state and status
            $orderStatus = $this->_order->getStatus();
            $orderState = $this->_order->getState();

            if (($orderState != Order::STATE_PROCESSING && $orderState != Order::STATE_PENDING_PAYMENT) ||
                ($orderStatus != self::STATUS_MAGENTO_AUTHORIZED)
            ) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Invalid order state or status.'));
            }
        } else {
            // Check if order has valid state and status
            $this->_verifyOrderState();
        }

        // Check if the transaction exists
        $this->_checkTransactionExists($txnId);

        $payment = $this->_order->getPayment();

        $parentTxnId = null;

        /* If the capture has a corresponding authorisation before
         * authorise -> capture
         */
        if ($isAuthAndCapture) {

            $authorizationTransaction = $payment->getAuthorizationTransaction();

            if (!$authorizationTransaction || !$authorizationTransaction->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Cannot find payment authorization transaction.')
                );
            }

            if ($authorizationTransaction->getTxnType() != \Magento\Sales\Model\Order\Payment\Transaction::TYPE_AUTH) {
                throw new \Magento\Framework\Exception\LocalizedException(__('Incorrect payment transaction type.'));
            }
            $parentTxnId = $authorizationTransaction->getTxnId();
        }

        if (!$this->_order->canInvoice()) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Cannot create invoice for the order.'));
        }

        $amount = $this->_order->getBaseTotalDue();

        if ($parentTxnId) {
            $payment->setParentTransactionId($parentTxnId);
            $payment->setShouldCloseParentTransaction(true);
        }

        // Capture
        $payment->setTransactionId($txnId)
            ->setPreparedMessage('')
            ->setIsTransactionClosed(0)
            ->registerCaptureNotification($amount);

        $this->_logger->info($this->_helper->__("Payment Captured"));

        $this->_orderRepository->save($this->_order);

        // Invoice
        $invoice = $payment->getCreatedInvoice();

        if ($invoice) {

            $this->_order->addStatusHistoryComment(
                $this->_helper->__(
                    'Notified customer about invoice #%s.',
                    $invoice->getIncrementId()
                )
            )->setIsCustomerNotified(true);

            $this->_orderRepository->save($this->_order);
        }
    }

    /**
     * Make sure addresses will be saved without validation errors
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _verifyOrderState()
    {
        $currentState = $this->_order->getState();

        if ($currentState != Order::STATE_NEW) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid order state.'));
        }
    }

    /**
     * Checks if transaction exists
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _checkTransactionExists($txnId)
    {
        $payment = $this->_order->getPayment();

        if ($payment && $payment->getId()) {
            $transaction = $payment->getTransaction($txnId);
            if ($transaction && $transaction->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The payment transaction already exists.')
                );
            }
        }
    }

    /**
     * Authorises the charge
     *
     */
    protected function _authorise($txnId)
    {
        // Check if order has valid state
        $this->_verifyOrderState();
        // Check if the transaction exists
        $this->_checkTransactionExists($txnId);

        $amount = $this->_order->getBaseTotalDue();

        $payment = $this->_order->getPayment();

        // Authorise the payment
        $payment->setTransactionId($txnId)
            ->setIsTransactionClosed(0)
            ->registerAuthorizationNotification($amount);

        $this->_logger->info($this->_helper->__("Payment Authorised"));

        $this->_order->setStatus(self::STATUS_MAGENTO_AUTHORIZED);

        $this->_orderRepository->save($this->_order);

        if ($this->_order->getCanSendNewEmailFlag()) {
            try {
                $this->_orderSender->send($this->_order);
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        }
    }

    /**
     * Places the order.
     *
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     * @throws \Magento\Sales\Model\Order
     */
    public function placeOrder()
    {
        $checkoutMethod = $this->getCheckoutMethod();

        $this->_logger->debug(
            $this->_helper->__(
                'Quote Grand Total:- %s Quote Customer Id:- %s Checkout Method:- %s',
                $this->_quote->getGrandTotal(),
                $this->_quote->getCustomerId(),
                $checkoutMethod
            )
        );

        $isNewCustomer = false;
        switch ($checkoutMethod) {
            case Onepage::METHOD_GUEST:
                $this->_prepareGuestQuote();
                break;
            case Onepage::METHOD_REGISTER:
                $this->_prepareNewCustomerQuote();
                $isNewCustomer = true;
                break;
            default:
                $this->_prepareCustomerQuote();
                break;
        }

        $this->_ignoreAddressValidation();
        $this->_quote->collectTotals();

        /* set customer email if email is found empty (bug) */
        if ($this->_quote->getCustomerEmail() === null && $this->_quote->getBillingAddress()->getEmail() !== null) {
            $this->_quote->setCustomerEmail($this->_quote->getBillingAddress()->getEmail());
        }

        $order = $this->_quoteManagement->submit($this->_quote);

        if ($isNewCustomer) {
            try {
                $this->_involveNewCustomer();
            } catch (\Exception $e) {
                $this->_logger->critical($e);
            }
        }

        $this->_order = $order;

        if (!$order) {
            $this->_logger->info(__('Couldnot place the order'));
            return false;
        }

        $this->_logger->info(__('Successfull to place the order'));

        /**
         * we only want to send to customer about new order when there is no redirect to third party
         */

        $this->_checkoutSession
            ->setLastQuoteId($this->getQuote()->getId())
            ->setLastSuccessQuoteId($this->getQuote()->getId())
            ->clearHelperData();

        // add order information to the session
        $this->_checkoutSession
            ->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());

        return $order;
    }

    /**
     * Prepare quote for guest checkout order submit
     *
     * @return \Zip\ZipPayment\Model\Charge
     */
    protected function _prepareGuestQuote()
    {
        $quote = $this->_quote;
        $quote->setCustomerId(null)
            ->setCustomerEmail($quote->getBillingAddress()->getEmail())
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(GroupInterface::NOT_LOGGED_IN_ID);
        return $this;
    }

    /**
     * Prepare quote for customer registration
     *
     * @return \Zip\ZipPayment\Model\Charge
     */
    protected function _prepareNewCustomerQuote()
    {
        $quote = $this->_quote;
        $billing = $quote->getBillingAddress();
        $shipping = $quote->isVirtual() ? null : $quote->getShippingAddress();

        $this->_logger->info($this->_helper->__('Creating new customer with email %s', $quote->getCustomerEmail()));

        $customer = $quote->getCustomer();
        // $customer->setEmail($billing->getEmail());
        // $quote->setCustomerEmail($billing->getEmail())
        //       ->setCustomerFirstname($billing->getFirstname())
        //       ->setCustomerMiddlename($billing->getMiddlename())
        //       ->setCustomerLastname($billing->getLastname());

        $customerBillingData = $billing->exportCustomerAddress();
        $dataArray = $this->_objectCopyService->getDataFromFieldset('checkout_onepage_quote', 'to_customer', $quote);
        $this->_dataObjectHelper->populateWithArray(
            $customer,
            $dataArray,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );
        $quote->setCustomer($customer)->setCustomerId(true);

        $customerBillingData->setIsDefaultBilling(true);

        if ($shipping) {
            if (!$shipping->getSameAsBilling()) {
                $customerShippingData = $shipping->exportCustomerAddress();
                $customerShippingData->setIsDefaultShipping(true);
                $shipping->setCustomerAddressData($customerShippingData);
                // Add shipping address to quote since customer Data Object does not hold address information
                $quote->addCustomerAddress($customerShippingData);
            } else {
                $shipping->setCustomerAddressData($customerBillingData);
                $customerBillingData->setIsDefaultShipping(true);
            }
        } else {
            $customerBillingData->setIsDefaultShipping(true);
        }

        $billing->setCustomerAddressData($customerBillingData);
        // Add billing address to quote since customer Data Object does not hold address information
        $quote->addCustomerAddress($customerBillingData);
        // $this->_quoteRepository->save($quote);

        $this->_logger->info($this->_helper->__(
            'The new customer has been created successfully. Customer id: %s',
            $customer->getId()
        ));

        return $this;
    }

    /**
     * Prepare quote for customer
     *
     * @return \Zip\ZipPayment\Model\Charge
     */
    protected function _prepareCustomerQuote()
    {
        $quote = $this->_quote;
        $billing = $quote->getBillingAddress();
        $shipping = $quote->isVirtual() ? null : $quote->getShippingAddress();

        if ($this->_getCustomerSession()->isLoggedIn()) {
            $this->_logger->debug($this->_helper->__('Load customer from session.'));
            $customer = $this->_customerRepository->getById($this->_getCustomerSession()->getCustomerId());
            $this->_logger->debug($this->_helper->__("Creating Order as Logged in Customer "));
        } else {
            $this->_logger->debug($this->_helper->__('Load customer from db.'));
            $customer = $this->_customerRepository->getById($quote->getCustomerId());
            $this->_logger->debug($this->_helper->__(
                "Creating Order on behalf of Customer %s",
                $quote->getCustomerId()
            ));
        }

        $hasDefaultBilling = (bool)$customer->getDefaultBilling();
        $hasDefaultShipping = (bool)$customer->getDefaultShipping();

        if ($shipping && !$shipping->getSameAsBilling() &&
            (!$shipping->getCustomerId() || $shipping->getSaveInAddressBook())
        ) {
            $shippingAddress = $shipping->exportCustomerAddress();
            if (!$hasDefaultShipping) {
                //Make provided address as default shipping address
                $shippingAddress->setIsDefaultShipping(true);
                $hasDefaultShipping = true;
            }
            $quote->addCustomerAddress($shippingAddress);
            $shipping->setCustomerAddressData($shippingAddress);
        }

        if (!$billing->getCustomerId() || $billing->getSaveInAddressBook()) {
            $billingAddress = $billing->exportCustomerAddress();
            if (!$hasDefaultBilling) {
                //Make provided address as default shipping address
                if (!$hasDefaultShipping) {
                    //Make provided address as default shipping address
                    $billingAddress->setIsDefaultShipping(true);
                }
                $billingAddress->setIsDefaultBilling(true);
            }
            $quote->addCustomerAddress($billingAddress);
            $billing->setCustomerAddressData($billingAddress);
        }
        return $this;
    }

    /**
     * Make sure addresses will be saved without validation errors
     */
    private function _ignoreAddressValidation()
    {
        $this->_quote->getBillingAddress()->setShouldIgnoreValidation(true);
        if (!$this->_quote->getIsVirtual()) {
            $this->_quote->getShippingAddress()->setShouldIgnoreValidation(true);
            if (!$this->_quote->getBillingAddress()->getEmail()) {
                $this->_quote->getBillingAddress()->setSameAsBilling(1);
            }
        }
    }

    /**
     * Involve new customer to system
     *
     * @return \Zip\ZipPayment\Model\Charge
     */
    protected function _involveNewCustomer()
    {
        $customer = $this->_quote->getCustomer();
        $confirmationStatus = $this->_accountManagement->getConfirmationStatus($customer->getId());
        if ($confirmationStatus === \Magento\Customer\Model\AccountManagement::ACCOUNT_CONFIRMATION_REQUIRED) {
            $url = $this->_customerUrl->getEmailConfirmationUrl($customer->getEmail());
            $this->_messageManager->addSuccess(
            // @codingStandardsIgnoreStart
                __(
                    'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                    $url
                )
            // @codingStandardsIgnoreEnd
            );
        } else {
            $this->_getCustomerSession()->loginById($customer->getId());
        }
        return $this;
    }
}
