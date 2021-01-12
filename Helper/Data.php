<?php

namespace Zip\ZipPayment\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Data extends AbstractHelper
{
    /**
     * @var \Zip\ZipPayment\Model\Config
     */
    private $_config = null;
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    private $_orderFactory = null;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $_orderRepository = null;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    private $_moduleList = null;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $_productMetadata = null;

    /**
     * Set quote and config instances
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Zip\ZipPayment\Model\Config $config
    ) {
        $this->_orderFactory = $orderFactory;
        $this->_orderRepository = $orderRepository;
        $this->_productMetadata = $productMetadata;
        $this->_moduleList = $moduleList;
        $this->_config = $config;
        parent::__construct($context);
    }

    /**
     * Returns the json_encoded string
     *
     * @return string
     */
    public function jsonEncode($object)
    {
        return json_encode(\Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($object));
    }

    /**
     * Handles the api exception
     *
     * @param ApiException $e
     * @return string
     */
    public function handleException($e)
    {
        if ($e instanceof \Zip\ZipPayment\MerchantApi\Lib\ApiException) {
            $apiError = '';
            $message = $this->__("Could not process the payment");
            switch ($e->getCode()) {
                case 0:
                    $logMessage = "Connection Error:- " . $e->getCode() . "-" . $e->getMessage();
                    break;
                case 201:
                case 400:
                case 401:
                case 402:
                case 403:
                case 409:
                    $logMessage = "ApiError:- " . $e->getMessage() . "-" . json_encode($e->getResponseBody());
                    $resObj = $e->getResponseObject();
                    $apiErrorCode = null;

                    if ($resObj && $resObj->getError()) {
                        $apiError = $resObj->getError()->getMessage();
                        $apiErrorCode = $resObj->getError()->getCode();
                    }

                    if ($e->getCode() == 402 &&
                        $mapped_error_code = $this->_config->getMappedErrorCode($apiErrorCode)) {
                        $message = $this->__('The payment was declined by Zip.(%s)', $mapped_error_code);
                    }

                    break;
                default:
                    $resObj = $e->getResponseObject();
                    $logMessage = "Error:- " . $e->getMessage() . "-" . json_encode($e->getResponseBody());
                    break;
            }

            $this->_logger->debug($logMessage);

            return [$apiError, $message, $logMessage];
        }
        return null;
    }

    /**
     * Prints the string with the given arguments
     *
     * @return string
     */
    public function __()
    {
        $args = func_get_args();
        $text = array_shift($args);

        return vsprintf(__($text), $args);
    }

    /**
     * Cancels the order
     *
     * @param \Magento\Sales\Model\Order $order
     * @param string $customer_email
     */
    public function cancelOrder($order, $order_comment = null)
    {
        if ($order) {
            if ($order_comment) {
                $order->addCommentToStatusHistory($order_comment);
                $this->_orderRepository->save($order);
            }

            $this->_logger->debug("Cancelling the order");

            if ($order->cancel()) {
                $this->_orderRepository->save($order);
            }
        }
    }

    /**
     * Generates uniq key
     *
     * @return string
     */
    public function generateIdempotencyKey()
    {
        return uniqid();
    }

    /**
     * Returns Magento Version
     *
     * @return string
     */
    public function getMagentoVersion()
    {
        return $this->_productMetadata->getVersion();
    }

    /**
     * Returns Module Version
     *
     * @return string
     */
    public function getExtensionVersion()
    {
        $moduleInfo = $this->_moduleList->getOne("Zip_ZipPayment");
        return $moduleInfo['setup_version'];
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _activateQuote($quote)
    {
        if ($quote && $quote->getId()) {
            if (!$quote->getIsActive()) {
                $orderIncId = $quote->getReservedOrderId();
                if ($orderIncId) {
                    $order = $this->_orderFactory->create()->loadByIncrementId($orderIncId);
                    if ($order && $order->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(
                            __('Can not activate the quote. It has already been converted to order.')
                        );
                    }
                }
                $quote->setIsActive(1)->save();
                $this->_logger->warning(__('Activated quote ' . $quote->getId() . '.'));
                return true;
            }
        }
        return false;
    }

    /**
     * Deactivates the quote
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @return bool
     */
    protected function _deactivateQuote($quote)
    {
        if ($quote && $quote->getId()) {
            if ($quote->getIsActive()) {
                $quote->setIsActive(0)->save();
                $this->_logger->warning(__('Deactivated quote ' . $quote->getId() . '.'));
                return true;
            }
        }
        return false;
    }
}
