<?php

namespace Zip\ZipPayment\Controller\Complete;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Result\Page;
use Zip\ZipPayment\Controller\Standard\AbstractStandard;
use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Index extends AbstractStandard
{
    /**
     * Valid Application Results
     *
     * @var array
     */
    protected $_validResults = ['approved', 'declined', 'cancelled', 'referred'];

    /**
     * Return from zipMoney and handle the result of the application
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $this->_logger->debug(__("On Complete Controller"));
        try {
            $this->_logger->debug($this->getRequest()->getRequestUri());
            $checkoutId = $this->getRequest()->getParam('checkoutId');
            $result = $this->getRequest()->getParam('result');
            $token = $this->getRequest()->getParam('token', null);

            $this->_logger->debug(__("Result:- %s", $result));
            // Is result valid ?
            if (!$this->_isResultValid()) {
                $this->_redirectToCartOrError();
                return;
            }

            if (!$checkoutId) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The checkoutId does not exist in the querystring.')
                );
            }

            // as AU stack already handle iframe in redirect
            $inContextCheckout = $this->_config->isInContextCheckout();
            $iframe = $this->getRequest()->getParam('iframe', null);
            if ($iframe && $this->_getCurrencyCode() != CommonUtil::CURRENCY_AUD && $inContextCheckout) {
                /** @var Page $page */
                $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
                /** @var Template $block */
                $layout = $page->getLayout();
                $block = $layout->createBlock(\Magento\Framework\View\Element\Template::class)
                    ->setTemplate('Zip_ZipPayment::iframe/iframe_js.phtml');
                $block->setData('checkoutId', $checkoutId);
                $block->setData('state', $result);
                $url = $this->_urlBuilder->getUrl('zippayment/complete')
                    . '?checkoutId=' . $checkoutId
                    . '&result=' . $result
                    . '&token=' . $token;
                $block->setData('redirectUrl', $url);
                /** @var \Magento\Framework\App\Response $response */
                $response = $this->getResponse();
                return $response->setBody($block->toHtml());
            }

            // Set the customer quote
            $this->_setCustomerQuote($token);
            // Initialise the charge
            $this->_initCharge();
            // Set quote to the chekout model
            $this->_charge->setQuote($this->_getQuote());
        } catch (\Exception $e) {

            $this->_logger->debug($e->getMessage());

            $this->_messageManager->addErrorMessage(__('Unable to complete the checkout.'));
            $this->_redirectToCartOrError();
            return;
        }

        /* Handle the application result */
        switch ($result) {

            case 'approved':
                /**
                 * Create order Charge the customer using the checkout id
                 */
                try {
                    // Create the Order
                    $order = $this->_charge->placeOrder();
                    $this->_charge->charge($token);

                    // update order status when successfully paid fix bug
                    // all order is pending deal to order and payment are async
                    if ($this->_config->isCharge()) {
                        $orderState = \Magento\Sales\Model\Order::STATE_PROCESSING;
                    } else {
                        $orderState = \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT;
                    }
                    $orderStatus = $order->getConfig()->getStateDefaultStatus($orderState);
                    $this->_logger->debug("Order captured setting order state: "
                        . $orderState . " status: " . $orderStatus);
                    $order->setState($orderState)->setStatus($orderStatus);
                    $order->save();

                    // Redirect to success page
                    return $this->getResponse()->setRedirect($this->getSuccessUrl());
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_messageManager->addErrorMessage($e->getMessage());
                    $this->_logger->debug($e->getMessage());
                }
                $this->_redirectToCartOrError();
                break;
            case 'declined':
                $this->_logger->debug(__('Calling declinedAction'));
                $this->_redirectToCart();
                break;
            case 'cancelled':
                $this->_logger->debug(__('Calling cancelledAction'));
                $this->_redirectToCart();
                break;
            case 'referred':
                // Make sure the qoute is active
                $this->_deactivateQuote($this->_getQuote());
                // Dispatch the referred action
                $this->_redirect($this->getReferredUrl());
                break;
            default:
                // Dispatch the referred action
                $this->_redirectToCartOrError();
                break;
        }
    }
}
