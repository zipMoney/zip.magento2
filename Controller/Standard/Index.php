<?php

namespace Zip\ZipPayment\Controller\Standard;

use Zip\ZipPayment\MerchantApi\Lib\Model\CommonUtil;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Index extends AbstractStandard
{
    /**
     * Start the checkout by requesting the redirect url and checkout id
     *
     * @return json
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        try {
            $this->_logger->info("Starting Checkout");
            // Do the checkout
            $token = false;
            if ($this->_customerSession->isLoggedIn()) {
                $rawData =  $this->_jsonHelper->jsonDecode($this->getRequest()->getContent());
                if (!empty($rawData)) {
                    if ($rawData['is_customer_want_tokenisation']) {
                        $token = $rawData['is_customer_want_tokenisation'];
                    }
                    if (!$rawData['is_customer_want_tokenisation']) {
                        $this->_removeCustomerToken();
                    }
                }
            }
            // Get the redirect url
            if (filter_var($token, FILTER_VALIDATE_BOOLEAN) && $this->_isCustomerSelectedTokenisationBefore()) {
                $redirect_url = $this->_urlBuilder->getUrl(
                    'zippayment/complete',
                    [
                        'checkoutId' => 'checkoutid',
                        'result' => self::CHECKOUT_STATUS_APPROVED,
                        'token' => true,
                        'iframe' => false,
                    ]
                );
                $data = [
                    'redirect_uri' => $redirect_url,
                    'message' => __('Redirecting for charge.')
                ];
                return $this->_sendResponse($data, \Magento\Framework\Webapi\Response::HTTP_OK);
            }
            $this->_initCheckout()->start($token);
            if ($redirectUrl = $this->_checkout->getRedirectUrl()) {
                $this->_logger->info(sprintf(__('Successful to get redirect url [ %s ] '), $redirectUrl));

                $data = [
                    'redirect_uri' => $redirectUrl,
                    'message' => __('Redirecting to Zip.')
                ];
                return $this->_sendResponse($data, \Magento\Framework\Webapi\Response::HTTP_OK);
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(__('Could not get the redirect url'));
            }
        } catch (\Exception $e) {
            $this->_logger->debug($e->getMessage());
            if (empty($result['error'])) {
                // $result['error'] = __('Can not get the redirect url from zipMoney.');
                $message = __("Can not get the redirect url from zipMoney.");
                if ($e->getCode() == 401 || $e->getCode() == 402) {
                    $message = __("Can not get the redirect url from zipMoney because of invalid zip api key.");
                }
                $result = [
                    'error' => true,
                    'message' => $message,
                    'error_message' => $e->getMessage(),
                    'code' => $e->getCode()
                ];
            }
            return $this->_sendResponse($result, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR);
        }
    }
}
