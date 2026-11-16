<?php

namespace Zip\ZipPayment\Controller\Adminhtml\HealthCheck;

use Zip\ZipPayment\Model\Config\Source\Environment;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultFactory;

    /**
     * @var \Zip\ZipPayment\Model\Config\HealthCheck
     */
    protected $_healthCheck;

    /**
     * @var \Zip\ZipPayment\Helper\Logger
     */
    protected $_logger;

    /**
     * @var Environment
     */
    private $environmentSource;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Zip\ZipPayment\Model\Config\HealthCheck $healthCheck
     * @param \Zip\ZipPayment\Helper\Logger $logger
     * @param Environment $environmentSource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Zip\ZipPayment\Model\Config\HealthCheck $healthCheck,
        \Zip\ZipPayment\Helper\Logger $logger,
        Environment $environmentSource
    ) {
        parent::__construct($context);
        $this->resultFactory = $resultJsonFactory;
        $this->_healthCheck = $healthCheck;
        $this->_logger = $logger;
        $this->environmentSource = $environmentSource;
    }

    /**
     * Ajax action for checking api credentials
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $apiKey = $this->getRequest()->getParam('apikey');
        $environment = $this->getRequest()->getParam('env');
        if (preg_match('/^[\*]+$/m', $apiKey ?? '')) {
            $apiKey = null;
        }
        $environmentList = $this->getEnvironmentList();
        if (!in_array($environment, $environmentList)) {
            $environment = null;
        }

        $websiteId = (int)$this->getRequest()->getParam('website', 0);
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create();
        try {
            $healthCheck = $this->_healthCheck->getHealthResult($websiteId, $apiKey, $environment);
            $response = $healthCheck;
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            $response = ['error' => 'true', 'message' => $e->getMessage()];
        }

        $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
        return $resultJson->setData($response);
    }

    /**
     * Return environments to check the credentials against
     *
     * @return string[]
     */
    private function getEnvironmentList()
    {
        return array_column($this->environmentSource->toOptionArray(), 'value');
    }
}
