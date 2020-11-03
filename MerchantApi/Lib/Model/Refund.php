<?php
/**
 * Refund
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */


namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class Refund implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'Refund';

    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = array(
        'id' => 'string',
        'charge_id' => 'string',
        'reason' => 'string',
        'amount' => 'float',
        'created' => '\DateTime',
        'metadata' => 'object'
    );
    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'id' => 'id',
        'charge_id' => 'charge_id',
        'reason' => 'reason',
        'amount' => 'amount',
        'created' => 'created',
        'metadata' => 'metadata'
    );
    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'id' => 'setId',
        'charge_id' => 'setChargeId',
        'reason' => 'setReason',
        'amount' => 'setAmount',
        'created' => 'setCreated',
        'metadata' => 'setMetadata'
    );
    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'id' => 'getId',
        'charge_id' => 'getChargeId',
        'reason' => 'getReason',
        'amount' => 'getAmount',
        'created' => 'getCreated',
        'metadata' => 'getMetadata'
    );
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
        $this->container['id'] = isset($data['id']) ? $data['id'] : null;
        $this->container['charge_id'] = isset($data['charge_id']) ? $data['charge_id'] : null;
        $this->container['reason'] = isset($data['reason']) ? $data['reason'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['created'] = isset($data['created']) ? $data['created'] : null;
        $this->container['metadata'] = isset($data['metadata']) ? $data['metadata'] : null;
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
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();

        if ($this->container['id'] === null) {
            $invalid_properties[] = "'id' can't be null";
        }
        if ($this->container['reason'] === null) {
            $invalid_properties[] = "'reason' can't be null";
        }
        if ($this->container['amount'] === null) {
            $invalid_properties[] = "'amount' can't be null";
        }
        if ($this->container['created'] === null) {
            $invalid_properties[] = "'created' can't be null";
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
        if ($this->container['reason'] === null) {
            return false;
        }
        if ($this->container['amount'] === null) {
            return false;
        }
        if ($this->container['created'] === null) {
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
     * @param string $id The id of the refund
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets charge_id
     * @return string
     */
    public function getChargeId()
    {
        return $this->container['charge_id'];
    }

    /**
     * Sets charge_id
     * @param string $charge_id The original charge that relates to this refund
     * @return $this
     */
    public function setChargeId($charge_id)
    {
        $this->container['charge_id'] = $charge_id;

        return $this;
    }

    /**
     * Gets reason
     * @return string
     */
    public function getReason()
    {
        return $this->container['reason'];
    }

    /**
     * Sets reason
     * @param string $reason The reason for the refund
     * @return $this
     */
    public function setReason($reason)
    {
        $this->container['reason'] = $reason;

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
     * @param float $amount The amount that was refunded
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets created
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->container['created'];
    }

    /**
     * Sets created
     * @param \DateTime $created The date the refund was created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->container['created'] = $created;

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
     * @param object $metadata Any additional metadata
     * @return $this
     */
    public function setMetadata($metadata)
    {
        $this->container['metadata'] = $metadata;

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
            return json_encode(\Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer::sanitizeForSerialization($this));
    }
}
