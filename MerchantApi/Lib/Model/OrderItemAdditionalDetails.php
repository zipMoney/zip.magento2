<?php
/**
 * OrderItemAdditionalDetails
 *
 * @category Class
 * @package  zipMoney
 * @author   Zip Plugin Team <integrations@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class OrderItemAdditionalDetails implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';
    const TYPE_FLIGHTS = 'Flights';
    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'OrderItem_additional_details';
    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'type' => 'string'
    ];

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'type' => 'type'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType'
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
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
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
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_FLIGHTS,
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

        $allowed_values = ["Flights"];
        if (!in_array($this->container['type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'type', must be one of 'Flights'.";
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

        $allowed_values = ["Flights"];
        if (!in_array($this->container['type'], $allowed_values)) {
            return false;
        }
        return true;
    }

    /**
     * Gets type
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $allowed_values = ['Flights'];
        if (!is_null($type) && (!in_array($type, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'type', must be one of 'Flights'");
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     * @param integer $offset Offset
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param integer $offset Offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
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
