<?php
/**
 * Shopper
 *
 * @category Class
 * @package  zipMoney
 * @author   Zip Plugin Team <integrations@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class Shopper implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';
    const GENDER_MALE = 'Male';
    const GENDER_FEMALE = 'Female';
    const GENDER_OTHER = 'Other';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'Shopper';
    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'title' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'middle_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'birth_date' => '\DateTime',
        'gender' => 'string',
        'statistics' => \Zip\ZipPayment\MerchantApi\Lib\Model\ShopperStatistics::class,
        'billing_address' => \Zip\ZipPayment\MerchantApi\Lib\Model\Address::class
    ];

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'title' => 'title',
        'first_name' => 'first_name',
        'last_name' => 'last_name',
        'middle_name' => 'middle_name',
        'phone' => 'phone',
        'email' => 'email',
        'birth_date' => 'birth_date',
        'gender' => 'gender',
        'statistics' => 'statistics',
        'billing_address' => 'billing_address'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'title' => 'setTitle',
        'first_name' => 'setFirstName',
        'last_name' => 'setLastName',
        'middle_name' => 'setMiddleName',
        'phone' => 'setPhone',
        'email' => 'setEmail',
        'birth_date' => 'setBirthDate',
        'gender' => 'setGender',
        'statistics' => 'setStatistics',
        'billing_address' => 'setBillingAddress'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'title' => 'getTitle',
        'first_name' => 'getFirstName',
        'last_name' => 'getLastName',
        'middle_name' => 'getMiddleName',
        'phone' => 'getPhone',
        'email' => 'getEmail',
        'birth_date' => 'getBirthDate',
        'gender' => 'getGender',
        'statistics' => 'getStatistics',
        'billing_address' => 'getBillingAddress'
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
        $this->container['title'] = isset($data['title']) ? $data['title'] : null;
        $this->container['first_name'] = isset($data['first_name']) ? $data['first_name'] : null;
        $this->container['last_name'] = isset($data['last_name']) ? $data['last_name'] : null;
        $this->container['middle_name'] = isset($data['middle_name']) ? $data['middle_name'] : null;
        $this->container['phone'] = isset($data['phone']) ? $data['phone'] : null;
        $this->container['email'] = isset($data['email']) ? $data['email'] : null;
        $this->container['birth_date'] = isset($data['birth_date']) ? $data['birth_date'] : null;
        $this->container['gender'] = isset($data['gender']) ? $data['gender'] : null;
        $this->container['statistics'] = isset($data['statistics']) ? $data['statistics'] : null;
        $this->container['billing_address'] = isset($data['billing_address']) ? $data['billing_address'] : null;
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
    public function getGenderAllowableValues()
    {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE,
            self::GENDER_OTHER,
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

        if ($this->container['first_name'] === null) {
            $invalid_properties[] = "'first_name' can't be null";
        }
        if ($this->container['last_name'] === null) {
            $invalid_properties[] = "'last_name' can't be null";
        }
        if (!is_null($this->container['phone']) && !preg_match("/^\\+?[\\d\\s]+$/", $this->container['phone'])) {
            $invalid_properties[] = "invalid value for 'phone', must be conform to the pattern /^\\+?[\\d\\s]+$/.";
        }

        if ($this->container['email'] === null) {
            $invalid_properties[] = "'email' can't be null";
        }
        $allowed_values = ["Male", "Female", "Other"];
        if (!in_array($this->container['gender'], $allowed_values)) {
            $invalid_properties[] = "invalid value for 'gender', must be one of 'Male', 'Female', 'Other'.";
        }

        if ($this->container['billing_address'] === null) {
            $invalid_properties[] = "'billing_address' can't be null";
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

        if ($this->container['first_name'] === null) {
            return false;
        }
        if ($this->container['last_name'] === null) {
            return false;
        }
        if (!preg_match("/^\\+?[\\d\\s]+$/", $this->container['phone'])) {
            return false;
        }
        if ($this->container['email'] === null) {
            return false;
        }
        $allowed_values = ["Male", "Female", "Other"];
        if (!in_array($this->container['gender'], $allowed_values)) {
            return false;
        }
        if ($this->container['billing_address'] === null) {
            return false;
        }
        return true;
    }

    /**
     * Gets title
     * @return string
     */
    public function getTitle()
    {
        return $this->container['title'];
    }

    /**
     * Sets title
     * @param string $title The shopper's title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->container['title'] = $title;

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
     * @param string $first_name The shopper's first name
     * @return $this
     */
    public function setFirstName($first_name)
    {
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
     * @param string $last_name The shopper's last name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->container['last_name'] = $last_name;

        return $this;
    }

    /**
     * Gets middle_name
     * @return string
     */
    public function getMiddleName()
    {
        return $this->container['middle_name'];
    }

    /**
     * Sets middle_name
     * @param string $middle_name The shopper's middle name
     * @return $this
     */
    public function setMiddleName($middle_name)
    {
        $this->container['middle_name'] = $middle_name;

        return $this;
    }

    /**
     * Gets phone
     * @return string
     */
    public function getPhone()
    {
        return $this->container['phone'];
    }

    /**
     * Sets phone
     * @param string $phone The shopper's contact phone number
     * @return $this
     */
    public function setPhone($phone)
    {

        if (!is_null($phone) && (!preg_match("/^\\+?[\\d\\s]+$/", $phone))) {
            throw new \InvalidArgumentException("Invalid value for $phone when calling Shopper, "
            . "must conform to the pattern /^\\+?[\\d\\s]+$/.");
        }

        $this->container['phone'] = $phone;

        return $this;
    }

    /**
     * Gets email
     * @return string
     */
    public function getEmail()
    {
        return $this->container['email'];
    }

    /**
     * Sets email
     * @param string $email The shopper's email address
     * @return $this
     */
    public function setEmail($email)
    {
        $this->container['email'] = $email;

        return $this;
    }

    /**
     * Gets birth_date
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->container['birth_date'];
    }

    /**
     * Sets birth_date
     * @param \DateTime $birth_date The shopper's birth date in the form yyyy-mm-dd
     * @return $this
     */
    public function setBirthDate($birth_date)
    {
        $this->container['birth_date'] = $birth_date;

        return $this;
    }

    /**
     * Gets gender
     * @return string
     */
    public function getGender()
    {
        return $this->container['gender'];
    }

    /**
     * Sets gender
     * @param string $gender The shopper's gender
     * @return $this
     */
    public function setGender($gender)
    {
        $allowed_values = ['Male', 'Female', 'Other'];
        if (!is_null($gender)) {
            $gender = ucfirst($gender);
        }
        if (!is_null($gender) && (!in_array($gender, $allowed_values))) {
            throw new \InvalidArgumentException("Invalid value for 'gender', must be one of 'Male', 'Female', 'Other'");
        }
        $this->container['gender'] = $gender;

        return $this;
    }

    /**
     * Gets statistics
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\ShopperStatistics
     */
    public function getStatistics()
    {
        return $this->container['statistics'];
    }

    /**
     * Sets statistics
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\ShopperStatistics $statistics
     * @return $this
     */
    public function setStatistics($statistics)
    {
        $this->container['statistics'] = $statistics;

        return $this;
    }

    /**
     * Gets billing_address
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Address
     */
    public function getBillingAddress()
    {
        return $this->container['billing_address'];
    }

    /**
     * Sets billing_address
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\Address $billing_address
     * @return $this
     */
    public function setBillingAddress($billing_address)
    {
        $this->container['billing_address'] = $billing_address;

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
