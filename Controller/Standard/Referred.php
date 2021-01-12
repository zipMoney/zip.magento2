<?php

namespace Zip\ZipPayment\Controller\Standard;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Referred extends AbstractStandard
{
    /**
     * Displays the referred view
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $this->_logger->info("In referredAction");

        try {
            $page_object = $this->_pageFactory->create();
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            $this->_messageManager->addErrorMessage(
                $this->_helper->__('An error occurred during redirecting to referred page')
            );
        }

        return $page_object;
    }
}
