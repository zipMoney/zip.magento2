<?php
/**
 * ShopperStatistics
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */


namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class ShopperStatistics implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'Shopper_statistics';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $zipTypes = array(
        'account_created' => '\DateTime',
        'sales_total_count' => 'int',
        'sales_total_amount' => 'float',
        'sales_avg_amount' => 'float',
        'sales_max_amount' => 'float',
        'refunds_total_amount' => 'float',
        'previous_chargeback' => 'bool',
        'currency' => 'string',
        'last_login' => '\DateTime',
        'has_previous_purchases' => 'bool',
        'fraud_check_result' => 'string'
    );

    public static function zipTypes()
    {
        return self::$zipTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'account_created' => 'account_created',
        'sales_total_count' => 'sales_total_count',
        'sales_total_amount' => 'sales_total_amount',
        'sales_avg_amount' => 'sales_avg_amount',
        'sales_max_amount' => 'sales_max_amount',
        'refunds_total_amount' => 'refunds_total_amount',
        'previous_chargeback' => 'previous_chargeback',
        'currency' => 'currency',
        'last_login' => 'last_login',
        'has_previous_purchases' => 'has_previous_purchases',
        'fraud_check_result' => 'fraud_check_result'
    );


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'account_created' => 'setAccountCreated',
        'sales_total_count' => 'setSalesTotalCount',
        'sales_total_amount' => 'setSalesTotalAmount',
        'sales_avg_amount' => 'setSalesAvgAmount',
        'sales_max_amount' => 'setSalesMaxAmount',
        'refunds_total_amount' => 'setRefundsTotalAmount',
        'previous_chargeback' => 'setPreviousChargeback',
        'currency' => 'setCurrency',
        'last_login' => 'setLastLogin',
        'has_previous_purchases' => 'setHasPreviousPurchases',
        'fraud_check_result' => 'setFraudCheckResult'
    );


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'account_created' => 'getAccountCreated',
        'sales_total_count' => 'getSalesTotalCount',
        'sales_total_amount' => 'getSalesTotalAmount',
        'sales_avg_amount' => 'getSalesAvgAmount',
        'sales_max_amount' => 'getSalesMaxAmount',
        'refunds_total_amount' => 'getRefundsTotalAmount',
        'previous_chargeback' => 'getPreviousChargeback',
        'currency' => 'getCurrency',
        'last_login' => 'getLastLogin',
        'has_previous_purchases' => 'getHasPreviousPurchases',
        'fraud_check_result' => 'getFraudCheckResult'
    );

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    public static function setters()
    {
        return self::$setters;
    }

    public static function getters()
    {
        return self::$getters;
    }

    const CURRENCY_AUD = 'AUD';
    const CURRENCY_NZD = 'NZD';
    const CURRENCY_GBP = 'GBP';
    const CURRENCY_USD = 'USD';
    const FRAUD_CHECK_RESULT_PASS = 'pass';
    const FRAUD_CHECK_RESULT_FAIL = 'fail';
    const FRAUD_CHECK_RESULT_UNKNOWN = 'unknown';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getCurrencyAllowableValues()
    {
        return array(
            self::CURRENCY_AUD,
            self::CURRENCY_NZD,
            self::CURRENCY_USD,
            self::CURRENCY_GBP,
        );
    }
    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getFraudCheckResultAllowableValues()
    {
        return array(
            self::FRAUD_CHECK_RESULT_PASS,
            self::FRAUD_CHECK_RESULT_FAIL,
            self::FRAUD_CHECK_RESULT_UNKNOWN,
        );
    }
    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = array();

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['account_created'] = isset($data['account_created']) ? $data['account_created'] : null;
        $this->container['sales_total_count'] = isset($data['sales_total_count']) ? $data['sales_total_count'] : null;
        $this->container['sales_total_amount'] = isset($data['sales_total_amount']) ? $data['sales_total_amount'] : null;
        $this->container['sales_avg_amount'] = isset($data['sales_avg_amount']) ? $data['sales_avg_amount'] : null;
        $this->container['sales_max_amount'] = isset($data['sales_max_amount']) ? $data['sales_max_amount'] : null;
        $this->container['refunds_total_amount'] = isset($data['refunds_total_amount']) ? $data['refunds_total_amount'] : null;
        $this->container['previous_chargeback'] = isset($data['previous_chargeback']) ? $data['previous_chargeback'] : null;
        $this->container['currency'] = isset($data['currency']) ? $data['currency'] : null;
        $this->container['last_login'] = isset($data['last_login']) ? $data['last_login'] : null;
        $this->container['has_previous_purchases'] = isset($data['has_previous_purchases']) ? $data['has_previous_purchases'] : null;
        $this->container['fraud_check_result'] = isset($data['fraud_check_result']) ? $data['fraud_check_result'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();

        $allowed_values = $this->getCurrencyAllowableValues();
        if (!in_array($this->container['currency'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'currency', must be one of '".implode("','",$allowed_values)."'.";
        }

        $allowed_values = array("pass", "fail", "unknown");
        if (!in_array($this->container['fraud_check_result'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'fraud_check_result', must be one of 'pass', 'fail', 'unknown'.";
        }

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {

        $allowed_values = $this->getCurrencyAllowableValues();
        if (!in_array($this->container['currency'], $allowed_values)) {
            return false;
        }
        $allowed_values = array("pass", "fail", "unknown");
        if (!in_array($this->container['fraud_check_result'], $allowed_values)) {
            return false;
        }
        return true;
    }


    /**
     * Gets account_created
     * @return \DateTime
     */
    public function getAccountCreated()
    {
        return $this->container['account_created'];
    }

    /**
     * Sets account_created
     * @param \DateTime $account_created The time at which the shopper's account was created
     * @return $this
     */
    public function setAccountCreated($account_created)
    {
        $this->container['account_created'] = $account_created;

        return $this;
    }

    /**
     * Gets sales_total_count
     * @return int
     */
    public function getSalesTotalCount()
    {
        return $this->container['sales_total_count'];
    }

    /**
     * Sets sales_total_count
     * @param int $sales_total_count The total number of separate purchases the shopper has made through the store
     * @return $this
     */
    public function setSalesTotalCount($sales_total_count)
    {
        $this->container['sales_total_count'] = $sales_total_count;

        return $this;
    }

    /**
     * Gets sales_total_amount
     * @return float
     */
    public function getSalesTotalAmount()
    {
        return $this->container['sales_total_amount'];
    }

    /**
     * Sets sales_total_amount
     * @param float $sales_total_amount The total purchase amount of all orders previously captured through the store.
     * @return $this
     */
    public function setSalesTotalAmount($sales_total_amount)
    {
        $this->container['sales_total_amount'] = $sales_total_amount;

        return $this;
    }

    /**
     * Gets sales_avg_amount
     * @return float
     */
    public function getSalesAvgAmount()
    {
        return $this->container['sales_avg_amount'];
    }

    /**
     * Sets sales_avg_amount
     * @param float $sales_avg_amount The average value of sales made by the shopper through the store
     * @return $this
     */
    public function setSalesAvgAmount($sales_avg_amount)
    {
        $this->container['sales_avg_amount'] = $sales_avg_amount;

        return $this;
    }

    /**
     * Gets sales_max_amount
     * @return float
     */
    public function getSalesMaxAmount()
    {
        return $this->container['sales_max_amount'];
    }

    /**
     * Sets sales_max_amount
     * @param float $sales_max_amount The maximum purchase amount the shopper has previously purchased from the store.
     * @return $this
     */
    public function setSalesMaxAmount($sales_max_amount)
    {
        $this->container['sales_max_amount'] = $sales_max_amount;

        return $this;
    }

    /**
     * Gets refunds_total_amount
     * @return float
     */
    public function getRefundsTotalAmount()
    {
        return $this->container['refunds_total_amount'];
    }

    /**
     * Sets refunds_total_amount
     * @param float $refunds_total_amount The total amount of all refunds linked to this shopper's account
     * @return $this
     */
    public function setRefundsTotalAmount($refunds_total_amount)
    {
        $this->container['refunds_total_amount'] = $refunds_total_amount;

        return $this;
    }

    /**
     * Gets previous_chargeback
     * @return bool
     */
    public function getPreviousChargeback()
    {
        return $this->container['previous_chargeback'];
    }

    /**
     * Sets previous_chargeback
     * @param bool $previous_chargeback Has the shopper had a previous chargeback?
     * @return $this
     */
    public function setPreviousChargeback($previous_chargeback)
    {
        $this->container['previous_chargeback'] = $previous_chargeback;

        return $this;
    }

    /**
     * Gets currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->container['currency'];
    }

    /**
     * Sets currency
     * @param string $currency The currency of all all amount values
     * @return $this
     */
    public function setCurrency($currency)
    {
        $allowed_values = $this->getCurrencyAllowableValues();
        if (!is_null($currency) && (!in_array($currency, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'currency', must be one of '".implode("','",$allowed_values)."'.");
        }
        $this->container['currency'] = $currency;

        return $this;
    }

    /**
     * Gets last_login
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->container['last_login'];
    }

    /**
     * Sets last_login
     * @param \DateTime $last_login The date at which the shopper last logged in to your store.
     * @return $this
     */
    public function setLastLogin($last_login)
    {
        $this->container['last_login'] = $last_login;

        return $this;
    }

    /**
     * Gets has_previous_purchases
     * @return bool
     */
    public function getHasPreviousPurchases()
    {
        return $this->container['has_previous_purchases'];
    }

    /**
     * Sets has_previous_purchases
     * @param bool $has_previous_purchases Does this customer have previous purchases at your store?
     * @return $this
     */
    public function setHasPreviousPurchases($has_previous_purchases)
    {
        $this->container['has_previous_purchases'] = $has_previous_purchases;

        return $this;
    }

    /**
     * Gets fraud_check_result
     * @return string
     */
    public function getFraudCheckResult()
    {
        return $this->container['fraud_check_result'];
    }

    /**
     * Sets fraud_check_result
     * @param string $fraud_check_result Merchant system's fraud check result
     * @return $this
     */
    public function setFraudCheckResult($fraud_check_result)
    {
        $allowed_values = array('pass', 'fail', 'unknown');
        if (!is_null($fraud_check_result) && (!in_array($fraud_check_result, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'fraud_check_result', must be one of 'pass', 'fail', 'unknown'");
        }
        $this->container['fraud_check_result'] = $fraud_check_result;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($this));
    }
}


