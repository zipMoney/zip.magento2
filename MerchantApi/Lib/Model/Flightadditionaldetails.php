<?php
/**
 * Flightadditionaldetails
 *
 * @category Class
 * @package  zipMoney
 * @author   Zip Plugin Team <integrations@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Model;

use \ArrayAccess;

class Flightadditionaldetails implements ArrayAccess
{
    const DISCRIMINATOR = 'subclass';

    /**
     * The original name of the model.
     * @var string
     */
    protected static $swaggerModelName = 'flightadditionaldetails';

    /**
     * Array of property to type mappings. Used for (de)serialization
     * @var string[]
     */
    protected static $zipTypes = [
        'departure_date' => '\DateTime',
        'flight_number' => 'string',
        'aircraft_type' => 'string',
        'class' => 'string',
        'origin' => 'string',
        'destination' => 'string',
        'duration' => 'string',
        'passengers' => '\Zip\ZipPayment\MerchantApi\Lib\Model\FlightadditionaldetailsPassengers[]',
        'stopovers' => 'string[]'
    ];

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = [
        'departure_date' => 'departure_date',
        'flight_number' => 'flight_number',
        'aircraft_type' => 'aircraft_type',
        'class' => 'class',
        'origin' => 'origin',
        'destination' => 'destination',
        'duration' => 'duration',
        'passengers' => 'passengers',
        'stopovers' => 'stopovers'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = [
        'departure_date' => 'setDepartureDate',
        'flight_number' => 'setFlightNumber',
        'aircraft_type' => 'setAircraftType',
        'class' => 'setClass',
        'origin' => 'setOrigin',
        'destination' => 'setDestination',
        'duration' => 'setDuration',
        'passengers' => 'setPassengers',
        'stopovers' => 'setStopovers'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = [
        'departure_date' => 'getDepartureDate',
        'flight_number' => 'getFlightNumber',
        'aircraft_type' => 'getAircraftType',
        'class' => 'getClass',
        'origin' => 'getOrigin',
        'destination' => 'getDestination',
        'duration' => 'getDuration',
        'passengers' => 'getPassengers',
        'stopovers' => 'getStopovers'
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
        $this->container['departure_date'] = isset($data['departure_date']) ? $data['departure_date'] : null;
        $this->container['flight_number'] = isset($data['flight_number']) ? $data['flight_number'] : null;
        $this->container['aircraft_type'] = isset($data['aircraft_type']) ? $data['aircraft_type'] : null;
        $this->container['class'] = isset($data['class']) ? $data['class'] : null;
        $this->container['origin'] = isset($data['origin']) ? $data['origin'] : null;
        $this->container['destination'] = isset($data['destination']) ? $data['destination'] : null;
        $this->container['duration'] = isset($data['duration']) ? $data['duration'] : null;
        $this->container['passengers'] = isset($data['passengers']) ? $data['passengers'] : null;
        $this->container['stopovers'] = isset($data['stopovers']) ? $data['stopovers'] : null;
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

        if ($this->container['departure_date'] === null) {
            $invalid_properties[] = "'departure_date' can't be null";
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

        if ($this->container['departure_date'] === null) {
            return false;
        }
        return true;
    }

    /**
     * Gets departure_date
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->container['departure_date'];
    }

    /**
     * Sets departure_date
     * @param \DateTime $departure_date
     * @return $this
     */
    public function setDepartureDate($departure_date)
    {
        $this->container['departure_date'] = $departure_date;

        return $this;
    }

    /**
     * Gets flight_number
     * @return string
     */
    public function getFlightNumber()
    {
        return $this->container['flight_number'];
    }

    /**
     * Sets flight_number
     * @param string $flight_number
     * @return $this
     */
    public function setFlightNumber($flight_number)
    {
        $this->container['flight_number'] = $flight_number;

        return $this;
    }

    /**
     * Gets aircraft_type
     * @return string
     */
    public function getAircraftType()
    {
        return $this->container['aircraft_type'];
    }

    /**
     * Sets aircraft_type
     * @param string $aircraft_type
     * @return $this
     */
    public function setAircraftType($aircraft_type)
    {
        $this->container['aircraft_type'] = $aircraft_type;

        return $this;
    }

    /**
     * Gets class
     * @return string
     */
    public function getClass()
    {
        return $this->container['class'];
    }

    /**
     * Sets class
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->container['class'] = $class;

        return $this;
    }

    /**
     * Gets origin
     * @return string
     */
    public function getOrigin()
    {
        return $this->container['origin'];
    }

    /**
     * Sets origin
     * @param string $origin
     * @return $this
     */
    public function setOrigin($origin)
    {
        $this->container['origin'] = $origin;

        return $this;
    }

    /**
     * Gets destination
     * @return string
     */
    public function getDestination()
    {
        return $this->container['destination'];
    }

    /**
     * Sets destination
     * @param string $destination
     * @return $this
     */
    public function setDestination($destination)
    {
        $this->container['destination'] = $destination;

        return $this;
    }

    /**
     * Gets duration
     * @return string
     */
    public function getDuration()
    {
        return $this->container['duration'];
    }

    /**
     * Sets duration
     * @param string $duration
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->container['duration'] = $duration;

        return $this;
    }

    /**
     * Gets passengers
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\FlightadditionaldetailsPassengers[]
     */
    public function getPassengers()
    {
        return $this->container['passengers'];
    }

    /**
     * Sets passengers
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\FlightadditionaldetailsPassengers[] $passengers
     * @return $this
     */
    public function setPassengers($passengers)
    {
        $this->container['passengers'] = $passengers;

        return $this;
    }

    /**
     * Gets stopovers
     * @return string[]
     */
    public function getStopovers()
    {
        return $this->container['stopovers'];
    }

    /**
     * Sets stopovers
     * @param string[] $stopovers
     * @return $this
     */
    public function setStopovers($stopovers)
    {
        $this->container['stopovers'] = $stopovers;

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
