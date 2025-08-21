<?php
/**
 * CheckoutOrder
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */
namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class CheckoutOrder implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'CheckoutOrder';

    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'reference' => 'string',
        'amount' => 'float',
        'currency' => 'string',
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
        'amount' => 'amount',
        'currency' => 'currency',
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
        'amount' => 'setAmount',
        'currency' => 'setCurrency',
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
        'amount' => 'getAmount',
        'currency' => 'getCurrency',
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
     * @param array|null $data Associated array of property values initializing the model
     */
    public function __construct(?array $data = null)
    {
        $this->container['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['currency'] = isset($data['currency']) ? $data['currency'] : null;
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

        if (!is_null($this->container['reference']) && (strlen($this->container['reference']) > 200)) {
            $invalid_properties[] = "invalid value for 'reference', "
            . "the character length must be smaller than or equal to 200.";
        }

        if ($this->container['amount'] === null) {
            $invalid_properties[] = "'amount' can't be null";
        }
        if (($this->container['amount'] < 0)) {
            $invalid_properties[] = "invalid value for 'amount', must be bigger than or equal to 0.";
        }

        if ($this->container['currency'] === null) {
            $invalid_properties[] = "'currency' can't be null";
        }
        if ($this->container['shipping'] === null) {
            $invalid_properties[] = "'shipping' can't be null";
        }
        if (!is_null($this->container['cart_reference']) && (strlen($this->container['cart_reference']) > 200)) {
            $invalid_properties[] = "invalid value for 'cart_reference', "
            . "the character length must be smaller than or equal to 200.";
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

        if (strlen($this->container['reference']) > 200) {
            return false;
        }
        if ($this->container['amount'] === null) {
            return false;
        }
        if ($this->container['amount'] < 0) {
            return false;
        }
        if ($this->container['currency'] === null) {
            return false;
        }
        if ($this->container['shipping'] === null) {
            return false;
        }
        if (strlen($this->container['cart_reference']) > 200) {
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
        if (!is_null($reference) && (strlen($reference) > 200)) {
            throw new \InvalidArgumentException('invalid length for $reference when calling CheckoutOrder, '
            . 'must be smaller than or equal to 200.');
        }

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
     * @param float $amount The total amount of the order
     * @return $this
     */
    public function setAmount($amount)
    {
        if (($amount < 0)) {
            throw new \InvalidArgumentException('Invalid value for $amount when calling CheckoutOrder, '
            . 'must be bigger than or equal to 0.');
        }
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
     * @param string $currency The ISO-4217 currency code. See https://en.wikipedia.org/wiki/ISO_4217
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->container['currency'] = $currency;

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
        if (!is_null($cart_reference) && (strlen($cart_reference) > 200)) {
            throw new \InvalidArgumentException('Invalid length for $cart_reference when calling CheckoutOrder, '
            . 'must be smaller than or equal to 200.');
        }

        $this->container['cart_reference'] = $cart_reference;

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
