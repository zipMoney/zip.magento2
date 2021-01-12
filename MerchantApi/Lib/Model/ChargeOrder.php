<?php
/**
 * ChargeOrder
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */
namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class ChargeOrder implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'ChargeOrder';

    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'reference' => 'string',
        'shipping' => \Zip\ZipPayment\MerchantApi\Lib\Model\OrderShipping::class,
        'items' => '\Zip\ZipPayment\MerchantApi\Lib\Model\OrderItem[]',
        'cart_reference' => 'string'
    ];

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'reference' => 'reference',
        'shipping' => 'shipping',
        'items' => 'items',
        'cart_reference' => 'cart_reference'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'reference' => 'setReference',
        'shipping' => 'setShipping',
        'items' => 'setItems',
        'cart_reference' => 'setCartReference'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'reference' => 'getReference',
        'shipping' => 'getShipping',
        'items' => 'getItems',
        'cart_reference' => 'getCartReference'
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
        $this->container['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $this->container['shipping'] = isset($data['shipping']) ? $data['shipping'] : null;
        $this->container['items'] = isset($data['items']) ? $data['items'] : null;
        $this->container['cart_reference'] = isset($data['cart_reference']) ? $data['cart_reference'] : null;
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

        if (!is_null($this->container['reference']) && (strlen($this->container['reference']) > 50)) {
            $invalid_properties[] = "invalid value for 'reference', "
            . "the character length must be smaller than or equal to 50.";
        }

        if ($this->container['shipping'] === null) {
            $invalid_properties[] = "'shipping' can't be null";
        }

        if (!is_null($this->container['cart_reference']) && (strlen($this->container['cart_reference']) > 100)) {
            $invalid_properties[] = "invalid value for 'cart_reference', "
            . "the character length must be smaller than or equal to 100.";
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
        if (strlen($this->container['reference']) > 50) {
            return false;
        }
        if ($this->container['shipping'] === null) {
            return false;
        }
        if (strlen($this->container['cart_reference']) > 100) {
            return false;
        }
        return true;
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
     * @param string $reference The order id in the eCommerce system
     * @return $this
     */
    public function setReference($reference)
    {
        if (!is_null($reference) && (strlen($reference) > 50)) {
            throw new \InvalidArgumentException('Invalid length for $reference when calling ChargeOrder, '
            . 'must be smaller than or equal to 50.');
        }

        $this->container['reference'] = $reference;

        return $this;
    }

    /**
     * Gets shipping
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\OrderShipping
     */
    public function getShipping()
    {
        return $this->container['shipping'];
    }

    /**
     * Sets shipping
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\OrderShipping $shipping
     * @return $this
     */
    public function setShipping($shipping)
    {
        $this->container['shipping'] = $shipping;

        return $this;
    }

    /**
     * Gets items
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\OrderItem[]
     */
    public function getItems()
    {
        return $this->container['items'];
    }

    /**
     * Sets items
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\OrderItem[] $items The order item breakdown
     * @return $this
     */
    public function setItems($items)
    {
        $this->container['items'] = $items;

        return $this;
    }

    /**
     * Gets cart_reference
     * @return string
     */
    public function getCartReference()
    {
        return $this->container['cart_reference'];
    }

    /**
     * Sets cart_reference
     * @param string $cart_reference The shopping cart reference id
     * @return $this
     */
    public function setCartReference($cart_reference)
    {
        if (!is_null($cart_reference) && (strlen($cart_reference) > 100)) {
            throw new \InvalidArgumentException('Invalid length for $cart_reference when calling ChargeOrder, '
            . 'must be smaller than or equal to 100.');
        }

        $this->container['cart_reference'] = $cart_reference;

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
