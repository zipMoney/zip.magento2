<?php
/**
 * OrderItem
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */


namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class OrderItem implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'OrderItem';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $zipTypes = array(
        'name' => 'string',
        'amount' => 'float',
        'reference' => 'string',
        'description' => 'string',
        'quantity' => 'float',
        'type' => 'string',
        'image_uri' => 'string',
        'item_uri' => 'string',
        'product_code' => 'string',
        'additional_details' => '\Zip\ZipPayment\MerchantApi\Lib\Model\OrderItemAdditionalDetails[]'
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
        'name' => 'name',
        'amount' => 'amount',
        'reference' => 'reference',
        'description' => 'description',
        'quantity' => 'quantity',
        'type' => 'type',
        'image_uri' => 'image_uri',
        'item_uri' => 'item_uri',
        'product_code' => 'product_code',
        'additional_details' => 'additional_details'
    );


    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'name' => 'setName',
        'amount' => 'setAmount',
        'reference' => 'setReference',
        'description' => 'setDescription',
        'quantity' => 'setQuantity',
        'type' => 'setType',
        'image_uri' => 'setImageUri',
        'item_uri' => 'setItemUri',
        'product_code' => 'setProductCode',
        'additional_details' => 'setAdditionalDetails'
    );


    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'name' => 'getName',
        'amount' => 'getAmount',
        'reference' => 'getReference',
        'description' => 'getDescription',
        'quantity' => 'getQuantity',
        'type' => 'getType',
        'image_uri' => 'getImageUri',
        'item_uri' => 'getItemUri',
        'product_code' => 'getProductCode',
        'additional_details' => 'getAdditionalDetails'
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

    const TYPE_SKU = 'sku';
    const TYPE_TAX = 'tax';
    const TYPE_SHIPPING = 'shipping';
    const TYPE_DISCOUNT = 'discount';
    const TYPE_STORE_CREDIT = 'store_credit';
    

    
    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return array(
            self::TYPE_SKU,
            self::TYPE_TAX,
            self::TYPE_SHIPPING,
            self::TYPE_DISCOUNT,
            self::TYPE_STORE_CREDIT,
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
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['reference'] = isset($data['reference']) ? $data['reference'] : null;
        $this->container['description'] = isset($data['description']) ? $data['description'] : null;
        $this->container['quantity'] = isset($data['quantity']) ? $data['quantity'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['image_uri'] = isset($data['image_uri']) ? $data['image_uri'] : null;
        $this->container['item_uri'] = isset($data['item_uri']) ? $data['item_uri'] : null;
        $this->container['product_code'] = isset($data['product_code']) ? $data['product_code'] : null;
        $this->container['additional_details'] = isset($data['additional_details']) ? $data['additional_details'] : null;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();

        if ($this->container['name'] === null) {
            $invalid_properties[] = "'name' can't be null";
        }
        if ($this->container['amount'] === null) {
            $invalid_properties[] = "'amount' can't be null";
        }
        if (!is_null($this->container['quantity']) && ($this->container['quantity'] <= 0)) {
            $invalid_properties[] = "invalid value for 'quantity', must be bigger than 0.";
        }

        if ($this->container['type'] === null) {
            $invalid_properties[] = "'type' can't be null";
        }
        $allowed_values = array("sku", "tax", "shipping", "discount", "store_credit");
        if (!in_array($this->container['type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'type', must be one of 'sku', 'tax', 'shipping', 'discount', 'store_credit'.";
        }

        if (!is_null($this->container['product_code']) && (strlen($this->container['product_code']) > 200)) {
            $invalid_properties[] = "invalid value for 'product_code', the character length must be smaller than or equal to 200.";
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

        if ($this->container['name'] === null) {
            return false;
        }
        if ($this->container['amount'] === null) {
            return false;
        }
        if ($this->container['quantity'] <= 0) {
            return false;
        }
        if ($this->container['type'] === null) {
            return false;
        }
        $allowed_values = array("sku", "tax", "shipping", "discount", "store_credit");
        if (!in_array($this->container['type'], $allowed_values)) {
            return false;
        }
        if (strlen($this->container['product_code']) > 200) {
            return false;
        }
        return true;
    }


    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

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
     * Gets description
     * @return string
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets quantity
     * @return float
     */
    public function getQuantity()
    {
        return $this->container['quantity'];
    }

    /**
     * Sets quantity
     * @param float $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {

        if (!is_null($quantity) && ($quantity <= 0)) {
            throw new \InvalidArgumentException('invalid value for $quantity when calling OrderItem., must be bigger than 0.');
        }

        $this->container['quantity'] = $quantity;

        return $this;
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
        $allowed_values = array('sku', 'tax', 'shipping', 'discount', 'store_credit');
        if ((!in_array($type, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'type', must be one of 'sku', 'tax', 'shipping', 'discount', 'store_credit'");
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets image_uri
     * @return string
     */
    public function getImageUri()
    {
        return $this->container['image_uri'];
    }

    /**
     * Sets image_uri
     * @param string $image_uri
     * @return $this
     */
    public function setImageUri($image_uri)
    {
        $this->container['image_uri'] = $image_uri;

        return $this;
    }

    /**
     * Gets item_uri
     * @return string
     */
    public function getItemUri()
    {
        return $this->container['item_uri'];
    }

    /**
     * Sets item_uri
     * @param string $item_uri
     * @return $this
     */
    public function setItemUri($item_uri)
    {
        $this->container['item_uri'] = $item_uri;

        return $this;
    }

    /**
     * Gets product_code
     * @return string
     */
    public function getProductCode()
    {
        return $this->container['product_code'];
    }

    /**
     * Sets product_code
     * @param string $product_code
     * @return $this
     */
    public function setProductCode($product_code)
    {
        if (!is_null($product_code) && (strlen($product_code) > 200)) {
            throw new \InvalidArgumentException('invalid length for $product_code when calling OrderItem., must be smaller than or equal to 200.');
        }

        $this->container['product_code'] = $product_code;

        return $this;
    }

    /**
     * Gets additional_details
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\OrderItemAdditionalDetails[]
     */
    public function getAdditionalDetails()
    {
        return $this->container['additional_details'];
    }

    /**
     * Sets additional_details
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\OrderItemAdditionalDetails[] $additional_details
     * @return $this
     */
    public function setAdditionalDetails($additional_details)
    {
        $this->container['additional_details'] = $additional_details;

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


