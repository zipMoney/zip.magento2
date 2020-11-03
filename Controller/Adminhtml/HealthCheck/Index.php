<?php

namespace Zip\ZipPayment\Controller\Adminhtml\HealthCheck;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultFactory;

    /**
     * @var Zip\ZipPayment\Model\Config\HealthCheck
     */
    protected $_healthCheck;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * //* @param \Zip\ZipPayment\Model\Config\HealthCheck $healthCheck
     */

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Zip\ZipPayment\Model\Config\HealthCheck $healthCheck
    )
    {
        parent::__construct($context);
        $this->resultFactory = $resultJsonFactory;
        $this->_healthCheck = $healthCheck;
    }

    /**
     * Ajax action for checking api credentials
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $websiteId = (int)$this->getRequest()->getParam('website', 0);
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create();
        try {
            $healthCheck = $this->_healthCheck->getHealthResult($websiteId);
            $response = $healthCheck;
        } catch (\Exception $e) {
            $response = ['error' => 'true', 'message' => $e->getMessage()];
        }

        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
        return $resultJson->setData($response);
    }

}
