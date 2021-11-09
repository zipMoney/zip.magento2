<?php

namespace Zip\ZipPayment\Model\Config\Source;

/**
 * @copyright 2020 Zip Co Limited
 * @link      https://zip.co
 */
class Region implements \Magento\Framework\Option\ArrayInterface
{
    protected $_availbaleCountries = array("au","gb","mx","nz","ca","us","ae","sg","za");
    protected $countryInformationAcquirer;

    public function __construct(
 
        \Magento\Directory\Api\CountryInformationAcquirerInterface $countryInformationAcquirer
   
    ) {
   
           $this->countryInformationAcquirer = $countryInformationAcquirer;
   
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $countries = $this->countryInformationAcquirer->getCountriesInfo();
         // Get all countries:
 
        $specificCountries = [];
 
        foreach ($countries as $country) {
            $countryCode = strtolower($country->getTwoLetterAbbreviation());
            if (in_array($countryCode, $this->_availbaleCountries)){
                $specificCountries[] = [
                    'value' => $countryCode,
                    'label' => $country->getFullNameEnglish()
                ];
            }
        }
        // you can use array_column() instead of the above code
        $label = array_column($specificCountries, 'label');
        // Sort the country with label ascending
        array_multisort( $label, SORT_ASC, $specificCountries);
        return $specificCountries;
    }
}
