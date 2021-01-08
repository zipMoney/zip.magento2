<?php

namespace Zip\ZipPayment\Gateway\Command;

use Magento\Payment\Gateway\CommandInterface;
use Psr\Log\LoggerInterface;

/**
 * @author    Zip Plugin Team <integration@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class InitializeStrategyCommand implements CommandInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

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
    public function execute(array $commandSubject)
    {
        // Gateway commands
    }
}
