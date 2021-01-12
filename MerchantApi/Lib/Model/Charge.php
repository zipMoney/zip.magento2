<?php
/**
 * Charge
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */
namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class Charge implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';
    const STATE_AUTHORISED = 'authorised';
    const STATE_CAPTURED = 'captured';
    const STATE_CANCELLED = 'cancelled';
    const STATE_DECLINED = 'declined';
    const STATE_REFUNDED = 'refunded';
    const STATE_APPROVED = 'approved';
    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'Charge';
    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'id' => 'string',
        'reference' => 'string',
        'amount' => 'float',
        'currency' => 'string',
        'state' => 'string',
        'captured_amount' => 'float',
        'refunded_amount' => 'float',
        'created_date' => '\DateTime',
        'order' => \Zip\ZipPayment\MerchantApi\Lib\Model\ChargeOrder::class,
        'metadata' => 'object',
        'receipt_number' => 'string'
    ];
    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'id' => 'id',
        'reference' => 'reference',
        'amount' => 'amount',
        'currency' => 'currency',
        'state' => 'state',
        'captured_amount' => 'captured_amount',
        'refunded_amount' => 'refunded_amount',
        'created_date' => 'created_date',
        'order' => 'order',
        'metadata' => 'metadata',
        'receipt_number' => 'receipt_number'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'id' => 'setId',
        'reference' => 'setReference',
        'amount' => 'setAmount',
        'currency' => 'setCurrency',
        'state' => 'setState',
        'captured_amount' => 'setCapturedAmount',
        'refunded_amount' => 'setRefundedAmount',
        'created_date' => 'setCreatedDate',
        'order' => 'setOrder',
        'metadata' => 'setMetadata',
        'receipt_number' => 'setReceiptNumber'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'id' => 'getId',
        'reference' => 'getReference',
        'amount' => 'getAmount',
        'currency' => 'getCurrency',
        'state' => 'getState',
        'captured_amount' => 'getCapturedAmount',
        'refunded_amount' => 'getRefundedAmount',
        'created_date' => 'getCreatedDate',
        'order' => 'getOrder',
        'metadata' => 'getMetadata',
        'receipt_number' => 'getReceiptNumber'
    ];
    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['currency'] = isset($data['currency']) ? $data['currency'] : null;
        $this->container['state'] = isset($data['state']) ? $data['state'] : null;
        $this->container['captured_amount'] = isset($data['captured_amount']) ? $data['captured_amount'] : null;
        $this->container['refunded_amount'] = isset($data['refunded_amount']) ? $data['refunded_amount'] : null;
        $this->container['created_date'] = isset($data['created_date']) ? $data['created_date'] : null;
        $this->container['order'] = isset($data['order']) ? $data['order'] : null;
        $this->container['metadata'] = isset($data['metadata']) ? $data['metadata'] : null;
        $this->container['receipt_number'] = isset($data['receipt_number']) ? $data['receipt_number'] : null;
    }

    public static function zipTypes()
    {
        return self::$zipTypes;
    }

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

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getStateAllowableValues()
    {
        return [
            self::STATE_AUTHORISED,
            self::STATE_CAPTURED,
            self::STATE_CANCELLED,
            self::STATE_DECLINED,
            self::STATE_REFUNDED,
            self::STATE_APPROVED,
        ];
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = [];

        if ($this->container['id'] === null) {
            $invalid_properties[] = "'id' can't be null";
        }
        if ($this->container['amount'] === null) {
            $invalid_properties[] = "'amount' can't be null";
        }
        if ($this->container['currency'] === null) {
            $invalid_properties[] = "'currency' can't be null";
        }
        if ($this->container['state'] === null) {
            $invalid_properties[] = "'state' can't be null";
        }
        $allowed_values = ["authorised", "captured", "cancelled", "refunded", "declined", "approved"];
        if (!in_array($this->container['state'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'state', "
            . "must be one of 'authorised', 'captured', 'cancelled', 'refunded', 'declined', 'approved'.";
        }

        if ($this->container['captured_amount'] === null) {
            $invalid_properties[] = "'captured_amount' can't be null";
        }
        if (($this->container['captured_amount'] < 0)) {
            $invalid_properties[] = "invalid value for 'captured_amount', must be bigger than or equal to 0.";
        }

        if ($this->container['refunded_amount'] === null) {
            $invalid_properties[] = "'refunded_amount' can't be null";
        }
        if (($this->container['refunded_amount'] < 0)) {
            $invalid_properties[] = "invalid value for 'refunded_amount', must be bigger than or equal to 0.";
        }

        if ($this->container['created_date'] === null) {
            $invalid_properties[] = "'created_date' can't be null";
        }
        if ($this->container['receipt_number'] === null) {
            $invalid_properties[] = "'receipt_number' can't be null";
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

        if ($this->container['id'] === null) {
            return false;
        }
        if ($this->container['amount'] === null) {
            return false;
        }
        if ($this->container['currency'] === null) {
            return false;
        }
        if ($this->container['state'] === null) {
            return false;
        }
        $allowed_values = ["authorised", "captured", "cancelled", "refunded", "declined", "approved"];
        if (!in_array($this->container['state'], $allowed_values)) {
            return false;
        }
        if ($this->container['captured_amount'] === null) {
            return false;
        }
        if ($this->container['captured_amount'] < 0) {
            return false;
        }
        if ($this->container['refunded_amount'] === null) {
            return false;
        }
        if ($this->container['refunded_amount'] < 0) {
            return false;
        }
        if ($this->container['created_date'] === null) {
            return false;
        }
        if ($this->container['receipt_number'] === null) {
            return false;
        }
        return true;
    }

    /**
     * Gets id
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets reference
     * @return string
     */
    public function getReference()
    {
        return $this->container['reference'];
    }

    /**
     * Sets reference
     * @param string $reference
     * @return $this
     */
    public function setReference($reference)
    {
        $this->container['reference'] = $reference;

        return $this;
    }

    /**
     * Gets amount
     * @return float
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

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
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->container['currency'] = $currency;

        return $this;
    }

    /**
     * Gets state
     * @return string
     */
    public function getState()
    {
        return $this->container['state'];
    }

    /**
     * Sets state
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $allowed_values = ['authorised', 'captured', 'cancelled', 'refunded', 'declined', "approved"];
        if ((!in_array($state, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'state', "
            . "must be one of 'authorised', 'captured', 'cancelled', 'refunded', 'declined', 'approved'");
        }
        $this->container['state'] = $state;

        return $this;
    }

    /**
     * Gets captured_amount
     * @return float
     */
    public function getCapturedAmount()
    {
        return $this->container['captured_amount'];
    }

    /**
     * Sets captured_amount
     * @param float $captured_amount
     * @return $this
     */
    public function setCapturedAmount($captured_amount)
    {
        if (($captured_amount < 0)) {
            throw new \InvalidArgumentException('Invalid value for $captured_amount when calling Charge, '
            . 'must be bigger than or equal to 0.');
        }

        $this->container['captured_amount'] = $captured_amount;

        return $this;
    }

    /**
     * Gets refunded_amount
     * @return float
     */
    public function getRefundedAmount()
    {
        return $this->container['refunded_amount'];
    }

    /**
     * Sets refunded_amount
     * @param float $refunded_amount The amount of the charge that has been refunded
     * @return $this
     */
    public function setRefundedAmount($refunded_amount)
    {

        if (($refunded_amount < 0)) {
            throw new \InvalidArgumentException('Invalid value for $refunded_amount when calling Charge, '
            . 'must be bigger than or equal to 0.');
        }

        $this->container['refunded_amount'] = $refunded_amount;

        return $this;
    }

    /**
     * Gets created_date
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->container['created_date'];
    }

    /**
     * Sets created_date
     * @param \DateTime $created_date
     * @return $this
     */
    public function setCreatedDate($created_date)
    {
        $this->container['created_date'] = $created_date;

        return $this;
    }

    /**
     * Gets order
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\ChargeOrder
     */
    public function getOrder()
    {
        return $this->container['order'];
    }

    /**
     * Sets order
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\ChargeOrder $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->container['order'] = $order;

        return $this;
    }

    /**
     * Gets metadata
     * @return object
     */
    public function getMetadata()
    {
        return $this->container['metadata'];
    }

    /**
     * Sets metadata
     * @param object $metadata
     * @return $this
     */
    public function setMetadata($metadata)
    {
        $this->container['metadata'] = $metadata;

        return $this;
    }

    /**
     * Gets receipt_number
     * @return string
     */
    public function getReceiptNumber()
    {
        return $this->container['receipt_number'];
    }

    /**
     * Sets receipt_number
     * @param string $receipt_number
     * @return $this
     */
    public function setReceiptNumber($receipt_number)
    {
        $this->container['receipt_number'] = $receipt_number;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     * @param integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param integer $offset Offset
     * @param mixed $value Value to be set
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
     * @param integer $offset Offset
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
            return json_encode(
                \Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(\Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($this));
    }
}
