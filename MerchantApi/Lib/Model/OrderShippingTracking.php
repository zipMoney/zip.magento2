<?php
/**
 * OrderShippingTracking
 *
 * @category Class
 * @package  zipMoney
 * @author   Zip Plugin Team <integrations@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class OrderShippingTracking implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'OrderShipping_tracking';

    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'uri' => 'string',
        'number' => 'string',
        'carrier' => 'string'
    ];

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'uri' => 'uri',
        'number' => 'number',
        'carrier' => 'carrier'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'uri' => 'setUri',
        'number' => 'setNumber',
        'carrier' => 'setCarrier'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'uri' => 'getUri',
        'number' => 'getNumber',
        'carrier' => 'getCarrier'
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
        $this->container['uri'] = isset($data['uri']) ? $data['uri'] : null;
        $this->container['number'] = isset($data['number']) ? $data['number'] : null;
        $this->container['carrier'] = isset($data['carrier']) ? $data['carrier'] : null;
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
        $invalid_properties = [];

        if (!is_null($this->container['uri']) && (strlen($this->container['uri']) > 500)) {
            $invalid_properties[] = "invalid value for 'uri', "
             . "the character length must be smaller than or equal to 500.";
        }

        if (!is_null($this->container['number']) && (strlen($this->container['number']) > 120)) {
            $invalid_properties[] = "invalid value for 'number', "
            . "the character length must be smaller than or equal to 120.";
        }

        if (!is_null($this->container['carrier']) && (strlen($this->container['carrier']) > 120)) {
            $invalid_properties[] = "invalid value for 'carrier', "
            . "the character length must be smaller than or equal to 120.";
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
        if (strlen($this->container['uri']) > 500) {
            return false;
        }
        if (strlen($this->container['number']) > 120) {
            return false;
        }
        if (strlen($this->container['carrier']) > 120) {
            return false;
        }
        return true;
    }

    /**
     * Gets uri
     * @return string
     */
    public function getUri()
    {
        return $this->container['uri'];
    }

    /**
     * Sets uri
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        if (!is_null($uri) && (strlen($uri) > 500)) {
            throw new \InvalidArgumentException('Invalid length for $uri when calling OrderShippingTracking, '
            . 'must be smaller than or equal to 500.');
        }

        $this->container['uri'] = $uri;

        return $this;
    }

    /**
     * Gets number
     * @return string
     */
    public function getNumber()
    {
        return $this->container['number'];
    }

    /**
     * Sets number
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        if (!is_null($number) && (strlen($number) > 120)) {
            throw new \InvalidArgumentException('Invalid length for $number when calling OrderShippingTracking, '
            . 'must be smaller than or equal to 120.');
        }

        $this->container['number'] = $number;

        return $this;
    }

    /**
     * Gets carrier
     * @return string
     */
    public function getCarrier()
    {
        return $this->container['carrier'];
    }

    /**
     * Sets carrier
     * @param string $carrier
     * @return $this
     */
    public function setCarrier($carrier)
    {
        if (!is_null($carrier) && (strlen($carrier) > 120)) {
            throw new \InvalidArgumentException('Invalid length for $carrier when calling OrderShippingTracking, '
            . 'must be smaller than or equal to 120.');
        }

        $this->container['carrier'] = $carrier;

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
