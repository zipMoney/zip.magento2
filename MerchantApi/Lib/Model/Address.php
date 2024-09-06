<?php
/**
 * Address
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */
namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class Address implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'address';

    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'line1' => 'string',
        'line2' => 'string',
        'city' => 'string',
        'state' => 'string',
        'postal_code' => 'string',
        'country' => 'string',
        'first_name' => 'string',
        'last_name' => 'string'
    ];

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'line1' => 'line1',
        'line2' => 'line2',
        'city' => 'city',
        'state' => 'state',
        'postal_code' => 'postal_code',
        'country' => 'country',
        'first_name' => 'first_name',
        'last_name' => 'last_name'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'line1' => 'setLine1',
        'line2' => 'setLine2',
        'city' => 'setCity',
        'state' => 'setState',
        'postal_code' => 'setPostalCode',
        'country' => 'setCountry',
        'first_name' => 'setFirstName',
        'last_name' => 'setLastName'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'line1' => 'getLine1',
        'line2' => 'getLine2',
        'city' => 'getCity',
        'state' => 'getState',
        'postal_code' => 'getPostalCode',
        'country' => 'getCountry',
        'first_name' => 'getFirstName',
        'last_name' => 'getLastName'
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
        $this->container['line1'] = isset($data['line1']) ? $data['line1'] : null;
        $this->container['line2'] = isset($data['line2']) ? $data['line2'] : null;
        $this->container['city'] = isset($data['city']) ? $data['city'] : null;
        $this->container['state'] = isset($data['state']) ? $data['state'] : null;
        $this->container['postal_code'] = isset($data['postal_code']) ? $data['postal_code'] : null;
        $this->container['country'] = isset($data['country']) ? $data['country'] : null;
        $this->container['first_name'] = isset($data['first_name']) ? $data['first_name'] : null;
        $this->container['last_name'] = isset($data['last_name']) ? $data['last_name'] : null;
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

        if ($this->container['line1'] === null) {
            $invalid_properties[] = "'line1' can't be null";
        }
        if ((strlen($this->container['line1']) > 200)) {
            $invalid_properties[] = "invalid value for 'line1', "
            . "the character length must be smaller than or equal to 200.";
        }

        if (!is_null($this->container['line2']) && (strlen($this->container['line2']) > 200)) {
            $invalid_properties[] = "invalid value for 'line2', "
            . "the character length must be smaller than or equal to 200.";
        }

        if ($this->container['city'] === null) {
            $invalid_properties[] = "'city' can't be null";
        }

        if ((strlen($this->container['city']) > 50)) {
            $invalid_properties[] = "invalid value for 'city', "
            . "the character length must be smaller than or equal to 50.";
        }

        if ($this->container['state'] === null) {
            $invalid_properties[] = "'state' can't be null";
        }
        if ((strlen($this->container['state']) > 50)) {
            $invalid_properties[] = "invalid value for 'state', "
            . "the character length must be smaller than or equal to 50.";
        }

        if ($this->container['postal_code'] === null) {
            $invalid_properties[] = "'postal_code' can't be null";
        }

        if ((strlen($this->container['postal_code']) > 15)) {
            $invalid_properties[] = "invalid value for 'postal_code', "
            . "the character length must be smaller than or equal to 15.";
        }

        if ($this->container['country'] === null) {
            $invalid_properties[] = "'country' can't be null";
        }

        if ((strlen($this->container['country']) > 2)) {
            $invalid_properties[] = "invalid value for 'country', "
            . "the character length must be smaller than or equal to 2.";
        }

        if ((strlen($this->container['country']) < 2)) {
            $invalid_properties[] = "invalid value for 'country', "
            . "the character length must be bigger than or equal to 2.";
        }

        if (!is_null($this->container['first_name']) && (strlen($this->container['first_name']) > 200)) {
            $invalid_properties[] = "invalid value for 'first_name', "
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
        if ($this->container['line1'] === null) {
            return false;
        }
        if (strlen($this->container['line1']) > 200) {
            return false;
        }
        if (strlen($this->container['line2']) > 200) {
            return false;
        }
        if ($this->container['city'] === null) {
            return false;
        }
        if (strlen($this->container['city']) > 50) {
            return false;
        }
        if ($this->container['state'] === null) {
            return false;
        }
        if (strlen($this->container['state']) > 50) {
            return false;
        }
        if ($this->container['postal_code'] === null) {
            return false;
        }
        if (strlen($this->container['postal_code']) > 15) {
            return false;
        }
        if ($this->container['country'] === null) {
            return false;
        }
        if (strlen($this->container['country']) > 2) {
            return false;
        }
        if (strlen($this->container['country']) < 2) {
            return false;
        }
        if (strlen($this->container['first_name']) > 200) {
            return false;
        }
        return true;
    }

    /**
     * Gets line1
     * @return string
     */
    public function getLine1()
    {
        return $this->container['line1'];
    }

    /**
     * Sets line1
     * @param string $line1 The first line in the address
     * @return $this
     */
    public function setLine1($line1)
    {
        if ((strlen($line1) > 200)) {
            throw new \InvalidArgumentException(
                'Invalid length for $line1 when calling Address, '
                . 'must be smaller than or equal to 200.'
            );
        }

        $this->container['line1'] = $line1;

        return $this;
    }

    /**
     * Gets line2
     * @return string
     */
    public function getLine2()
    {
        return $this->container['line2'];
    }

    /**
     * Sets line2
     * @param string $line2 The (optional) second address line
     * @return $this
     */
    public function setLine2($line2)
    {
        if (!is_null($line2) && (strlen($line2) > 200)) {
            throw new \InvalidArgumentException('Invalid length for $line2 when calling Address, '
            . 'must be smaller than or equal to 200.');
        }

        $this->container['line2'] = $line2;

        return $this;
    }

    /**
     * Gets city
     * @return string
     */
    public function getCity()
    {
        return $this->container['city'];
    }

    /**
     * Sets city
     * @param string $city The address city
     * @return $this
     */
    public function setCity($city)
    {
        if ((strlen($city) > 50)) {
            throw new \InvalidArgumentException('Invalid length for $city when calling Address, '
            . 'must be smaller than or equal to 50.');
        }

        $this->container['city'] = $city;

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
     * @param string $state The state or province
     * @return $this
     */
    public function setState(string $state)
    {
        if (empty($state)) {
            return $this;
        }

        if ((strlen($state) > 50)) {
            throw new \InvalidArgumentException('Invalid length for $state when calling Address, '
            . 'must be smaller than or equal to 50.');
        }

        $this->container['state'] = $state;

        return $this;
    }

    /**
     * Gets postal_code
     * @return string
     */
    public function getPostalCode()
    {
        return $this->container['postal_code'];
    }

    /**
     * Sets postal_code
     * @param string $postal_code The post or zip code
     * @return $this
     */
    public function setPostalCode($postal_code)
    {
        if ((strlen($postal_code) > 15)) {
            throw new \InvalidArgumentException('Invalid length for $postal_code when calling Address, '
            . 'must be smaller than or equal to 15.');
        }

        $this->container['postal_code'] = $postal_code;

        return $this;
    }

    /**
     * Gets country
     * @return string
     */
    public function getCountry()
    {
        return $this->container['country'];
    }

    /**
     * Sets country
     * @param string $country The ISO-3166 country code. See https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2.
     * @return $this
     */
    public function setCountry($country)
    {
        if ((strlen($country) > 2)) {
            throw new \InvalidArgumentException('Invalid length for $country when calling Address, '
            . 'must be smaller than or equal to 2.');
        }
        if ((strlen($country) < 2)) {
            throw new \InvalidArgumentException('Invalid length for $country when calling Address, '
            . 'must be bigger than or equal to 2.');
        }

        $this->container['country'] = $country;

        return $this;
    }

    /**
     * Gets first_name
     * @return string
     */
    public function getFirstName()
    {
        return $this->container['first_name'];
    }

    /**
     * Sets first_name
     * @param string $first_name The recipient's first name
     * @return $this
     */
    public function setFirstName($first_name)
    {
        if (!is_null($first_name) && (strlen($first_name) > 200)) {
            throw new \InvalidArgumentException('Invalid length for $first_name when calling Address, '
            . 'must be smaller than or equal to 200.');
        }

        $this->container['first_name'] = $first_name;

        return $this;
    }

    /**
     * Gets last_name
     * @return string
     */
    public function getLastName()
    {
        return $this->container['last_name'];
    }

    /**
     * Sets last_name
     * @param string $last_name The recipient's last name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->container['last_name'] = $last_name;

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
