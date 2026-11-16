<?php

namespace Zip\ZipPayment\Gateway\Command;

use Magento\Payment\Gateway\CommandInterface;
use Psr\Log\LoggerInterface;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class InitializeStrategyCommand implements CommandInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * Executes command basing on business object
     *
     * @param array $commandSubject
     * @return void
     */
    // @codingStandardsIgnoreStart
    public function execute(array $commandSubject)
    {
        // Gateway commands
        // @codingStandardsIgnoreEnd
    }
}
