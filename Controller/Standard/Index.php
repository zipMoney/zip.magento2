<?php

namespace Zip\ZipPayment\Controller\Standard;

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
            $this->_initCheckout()->start();

            // Get the redirect url
            if ($redirectUrl = $this->_checkout->getRedirectUrl()) {

                if ($this->_config->isInContextCheckout()) {
                    $redirectUrl .= '&embedded=true';
                }
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
                $message = "Can not get the redirect url from zipMoney.";
                if ($e->getCode() == 401 || $e->getCode() == 402) {
                    $message = "Can not get the redirect url from zipMoney because of inavlid zip api key.";
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
