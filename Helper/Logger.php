<?php
namespace Zip\ZipPayment\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Zip\ZipPayment\Logger\Logger as ZipMoneyLogger;

/**
 * @author    Zip Plugin Team <integrations@zip.co>
 * @copyright 2020 Zip Co Limited
 * @link      http://zip.co
 */
class Logger extends AbstractHelper
{
    /**
     * @var array
     */
    protected $_logLevelsMap = [  // Corresponding log levels in Magento 1.x and used in zipMoney Admins
        0 => 600, // 'EMERGENCY',
        1 => 550, // 'ALERT',
        2 => 500, // 'CRITICAL',
        3 => 400, // 'ERROR',
        4 => 300, // 'WARNING',
        5 => 250, // 'NOTICE',
        6 => 200, // 'INFO' ,
        7 => 100, // 'DEBUG'
    ];
    protected $_privateDataKeys = null;
    /**
     * Logs the info message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function info($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::INFO);
    }

    /**
     * Writes the log to the logfile
     *
     * @param string $message , int $logLevel, int $storeId
     * @return bool
     */
    protected function _log($message, $logLevel = ZipMoneyLogger::INFO, $storeId = null)
    {
        $path = "payment/"
            . \Zip\ZipPayment\Model\Config::METHOD_CODE
            . "/" . \Zip\ZipPayment\Model\Config::PAYMENT_ZIPMONEY_LOG_SETTINGS;
        $configLevel = $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($configLevel < 0) {
            return false;
        }

        // errors are always logged.
        if ($configLevel > 400) {
            $configLevel = ZipMoneyLogger::INFO; // default log level
        }

        if ($logLevel < $configLevel) {
            return false;
        }
        $this->_logger->log($logLevel, $message);
        return true;
    }

    /**
     * Logs the debug message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function debug($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::DEBUG);
    }

    /**
     * Logs the warn message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function warn($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::WARNING);
    }

    /**
     * Logs the notice message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function notice($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::NOTICE);
    }

    /**
     * Logs the error message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function error($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::ERROR);
    }

    /**
     * Logs the critical message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function critical($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::CRITICAL);
    }

    /**
     * Logs the alert message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function alert($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::ALERT);
    }

    /**
     * Logs the emergency message to the logfile
     *
     * @param string $message , int $storeId
     */
    public function emergency($message, $storeId = null)
    {
        $this->_log($message, ZipMoneyLogger::EMERGENCY);
    }

    /**
     * hide the customer privacy data from our log
     * such as phone, address, email, lastname, dob
     */
    protected function sanitizeArrData($debugData)
    {
        $privateData = $this->getPrivateData();
        if (is_array($debugData) && !empty($privateData)) {
            foreach ($debugData as $key => $val) {
                if (is_array($val)) {
                    $debugData[$key] = $this->sanitizeArrData($debugData[$key]);
                } elseif (in_array($key, $this->getPrivateData()) && !is_numeric($key)) {
                    $debugData[$key] = '****';
                } elseif (stristr($val, 'Authorization: Bearer') !== false) {
                    $debugData[$key] = '****';
                }
            }
        }

        return $debugData;
    }

    /**
     * Need to protect privacy here before we logging
     */
    public function getPrivateData()
    {
        return [
            'line1',
            'line2',
            'last_name',
            'phone',
            'email',
            'birth_date',
            'value'
        ];
    }

    public function sanitizePrivateData($debug)
    {
        if (is_null($debug)) {
            return null;
        }

        if (is_scalar($debug) || is_array(json_decode($debug, true))) {
            $json = json_decode($debug, true);
            if (is_array($json)) {
                return json_encode($this->sanitizeArrData($json));
            }
        } elseif (is_array($debug)) {
            return json_encode($this->sanitizeArrData($debug));
        }
        return (string) $debug;
    }
}
