<?php
/**
 * Checkout
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */


namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class Checkout implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';
    const TYPE_STANDARD = 'standard';
    const TYPE_EXPRESS = 'express';
    const STATE_CREATED = 'created';
    const STATE_EXPIRED = 'expired';
    const STATE_APPROVED = 'approved';
    const STATE_COMPLETED = 'completed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_DECLINED = 'declined';
    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'Checkout';
    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = array(
        'id' => 'string',
        'uri' => 'string',
        'type' => 'string',
        'shopper' => '\Zip\ZipPayment\MerchantApi\Lib\Model\Shopper',
        'order' => '\Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutOrder',
        'features' => '\Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutFeatures',
        'config' => '\Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutConfiguration',
        'created' => '\DateTime',
        'state' => 'string',
        'customer_id' => 'string',
        'metadata' => '\Zip\ZipPayment\MerchantApi\Lib\Model\Metadata'
    );
    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'id' => 'id',
        'uri' => 'uri',
        'type' => 'type',
        'shopper' => 'shopper',
        'order' => 'order',
        'features' => 'features',
        'config' => 'config',
        'created' => 'created',
        'state' => 'state',
        'customer_id' => 'customer_id',
        'metadata' => 'metadata'
    );
    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'id' => 'setId',
        'uri' => 'setUri',
        'type' => 'setType',
        'shopper' => 'setShopper',
        'order' => 'setOrder',
        'features' => 'setFeatures',
        'config' => 'setConfig',
        'created' => 'setCreated',
        'state' => 'setState',
        'customer_id' => 'setCustomerId',
        'metadata' => 'setMetadata'
    );
    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'id' => 'getId',
        'uri' => 'getUri',
        'type' => 'getType',
        'shopper' => 'getShopper',
        'order' => 'getOrder',
        'features' => 'getFeatures',
        'config' => 'getConfig',
        'created' => 'getCreated',
        'state' => 'getState',
        'customer_id' => 'getCustomerId',
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
        $this->container['uri'] = isset($data['uri']) ? $data['uri'] : null;
        $this->container['type'] = isset($data['type']) ? $data['type'] : 'standard';
        $this->container['shopper'] = isset($data['shopper']) ? $data['shopper'] : null;
        $this->container['order'] = isset($data['order']) ? $data['order'] : null;
        $this->container['features'] = isset($data['features']) ? $data['features'] : null;
        $this->container['config'] = isset($data['config']) ? $data['config'] : null;
        $this->container['created'] = isset($data['created']) ? $data['created'] : null;
        $this->container['state'] = isset($data['state']) ? $data['state'] : null;
        $this->container['customer_id'] = isset($data['customer_id']) ? $data['customer_id'] : null;
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
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return array(
            self::TYPE_STANDARD,
            self::TYPE_EXPRESS,
        );
    }

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public function getStateAllowableValues()
    {
        return array(
            self::STATE_CREATED,
            self::STATE_EXPIRED,
            self::STATE_APPROVED,
            self::STATE_COMPLETED,
            self::STATE_CANCELLED,
            self::STATE_DECLINED,
        );
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
        if ($this->container['uri'] === null) {
            $invalid_properties[] = "'uri' can't be null";
        }
        $allowed_values = array("standard", "express");
        if (!in_array($this->container['type'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'type', must be one of 'standard', 'express'.";
        }

        if ($this->container['created'] === null) {
            $invalid_properties[] = "'created' can't be null";
        }
        if ($this->container['state'] === null) {
            $invalid_properties[] = "'state' can't be null";
        }
        $allowed_values = array("created", "expired", "approved", "completed", "cancelled", "declined");
        if (!in_array($this->container['state'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'state', must be one of 'created', 'expired', 'approved', 'completed', 'cancelled', 'declined'.";
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
        if ($this->container['uri'] === null) {
            return false;
        }
        $allowed_values = array("standard", "express");
        if (!in_array($this->container['type'], $allowed_values)) {
            return false;
        }
        if ($this->container['created'] === null) {
            return false;
        }
        if ($this->container['state'] === null) {
            return false;
        }
        $allowed_values = array("created", "expired", "approved", "completed", "cancelled", "declined");
        if (!in_array($this->container['state'], $allowed_values)) {
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
     * @param string $id The checkout id
     * @return $this
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
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
     * @param string $uri The uri to redirect the user to in order to approve this checkout.
     * @return $this
     */
    public function setUri($uri)
    {
        $this->container['uri'] = $uri;

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
     * @param string $type The type of checkout
     * @return $this
     */
    public function setType($type)
    {
        $allowed_values = array('standard', 'express');
        if (!is_null($type) && (!in_array($type, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'type', must be one of 'standard', 'express'");
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets shopper
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Shopper
     */
    public function getShopper()
    {
        return $this->container['shopper'];
    }

    /**
     * Sets shopper
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\Shopper $shopper
     * @return $this
     */
    public function setShopper($shopper)
    {
        $this->container['shopper'] = $shopper;

        return $this;
    }

    /**
     * Gets order
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutOrder
     */
    public function getOrder()
    {
        return $this->container['order'];
    }

    /**
     * Sets order
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutOrder $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->container['order'] = $order;

        return $this;
    }

    /**
     * Gets features
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutFeatures
     */
    public function getFeatures()
    {
        return $this->container['features'];
    }

    /**
     * Sets features
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutFeatures $features
     * @return $this
     */
    public function setFeatures($features)
    {
        $this->container['features'] = $features;

        return $this;
    }

    /**
     * Gets config
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutConfiguration
     */
    public function getConfig()
    {
        return $this->container['config'];
    }

    /**
     * Sets config
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CheckoutConfiguration $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->container['config'] = $config;

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
     * @param \DateTime $created Date the checkout was created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->container['created'] = $created;

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
     * @param string $state Current state of the checkout
     * @return $this
     */
    public function setState($state)
    {
        $allowed_values = array('created', 'expired', 'approved', 'completed', 'cancelled', 'declined');
        if ((!in_array($state, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'state', must be one of 'created', 'expired', 'approved', 'completed', 'cancelled', 'declined'");
        }
        $this->container['state'] = $state;

        return $this;
    }

    /**
     * Gets customer_id
     * @return string
     */
    public function getCustomerId()
    {
        return $this->container['customer_id'];
    }

    /**
     * Sets customer_id
     * @param string $customer_id The id of the customer who has approved this checkout request. Only present if approved.
     * @return $this
     */
    public function setCustomerId($customer_id)
    {
        $this->container['customer_id'] = $customer_id;

        return $this;
    }

    /**
     * Gets metadata
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Metadata
     */
    public function getMetadata()
    {
        return $this->container['metadata'];
    }

    /**
     * Sets metadata
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\Metadata $metadata
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
