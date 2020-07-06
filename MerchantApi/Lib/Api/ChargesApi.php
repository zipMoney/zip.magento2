<?php
/**
 * ChargesApi
 *
 * @category Class
 * @package  zipMoney
 * @author    Zip Plugin Team <integration@zip.co>
 * @link     https://github.com/zipMoney/merchantapi-php
 */

namespace Zip\ZipPayment\MerchantApi\Lib\Api;

use \Zip\ZipPayment\MerchantApi\Lib\ApiClient;
use \Zip\ZipPayment\MerchantApi\Lib\ApiException;
use \Zip\ZipPayment\MerchantApi\Lib\Configuration;
use \Zip\ZipPayment\MerchantApi\Lib\ObjectSerializer;

class ChargesApi
{
    /**
     * API Client
     *
     * @var \Zip\ZipPayment\MerchantApi\Lib\ApiClient instance of the ApiClient
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param \Zip\ZipPayment\MerchantApi\Lib\ApiClient|null $apiClient The api client to use
     */
    public function __construct(\Zip\ZipPayment\MerchantApi\Lib\ApiClient $apiClient = null)
    {
        if ($apiClient === null) {
            $apiClient = new ApiClient();
        }
        $this->apiClient = $apiClient;
    }

    /**
     * Get API client
     *
     * @return \Zip\ZipPayment\MerchantApi\Lib\ApiClient get the API client
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * Set the API client
     *
     * @param \Zip\ZipPayment\MerchantApi\Lib\ApiClient $apiClient set the API client
     *
     * @return ChargesApi
     */
    public function setApiClient(\Zip\ZipPayment\MerchantApi\Lib\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    /**
     * Operation chargesCancel
     *
     * Cancel a charge
     *
     * @param string $id The id of the authorised charge (required)
     * @param string $idempotency_key The unique idempotency key. (optional)
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesCancel($id, $idempotency_key = null)
    {
        list($response) = $this->chargesCancelWithHttpInfo($id, $idempotency_key);
        return $response;
    }

    /**
     * Operation chargesCancelWithHttpInfo
     *
     * Cancel a charge
     *
     * @param string $id The id of the authorised charge (required)
     * @param string $idempotency_key The unique idempotency key. (optional)
     * @return array of \Zip\ZipPayment\MerchantApi\Lib\Model\Charge, HTTP status code, HTTP response headers (array of strings)
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesCancelWithHttpInfo($id, $idempotency_key = null)
    {
        // verify the required parameter 'id' is set
        if ($id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $id when calling chargesCancel');
        }
        // parse inputs
        $resourcePath = "/charges/{id}/cancel";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/javascript'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json'));

        // header params
        if ($idempotency_key !== null) {
            $headerParams['Idempotency-Key'] = $this->apiClient->getSerializer()->toHeaderValue($idempotency_key);
        }
        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                "{" . "id" . "}",
                $this->apiClient->getSerializer()->toPathValue($id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('Authorization');
        if (strlen($apiKey) !== 0) {
            $headerParams['Authorization'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge',
                '/charges/{id}/cancel'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 402:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 409:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation chargesCapture
     *
     * Capture a charge
     *
     * @param string $id The id of the authorised charge (required)
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CaptureChargeRequest $body  (optional)
     * @param string $idempotency_key The unique idempotency key. (optional)
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesCapture($id, $body = null, $idempotency_key = null)
    {
        list($response) = $this->chargesCaptureWithHttpInfo($id, $body, $idempotency_key);
        return $response;
    }

    /**
     * Operation chargesCaptureWithHttpInfo
     *
     * Capture a charge
     *
     * @param string $id The id of the authorised charge (required)
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CaptureChargeRequest $body  (optional)
     * @param string $idempotency_key The unique idempotency key. (optional)
     * @return array of \\Zip\ZipPayment\MerchantApi\Lib\Model\Charge, HTTP status code, HTTP response headers (array of strings)
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesCaptureWithHttpInfo($id, $body = null, $idempotency_key = null)
    {
        // verify the required parameter 'id' is set
        if ($id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $id when calling chargesCapture');
        }
        // parse inputs
        $resourcePath = "/charges/{id}/capture";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/javascript'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json'));

        // header params
        if ($idempotency_key !== null) {
            $headerParams['Idempotency-Key'] = $this->apiClient->getSerializer()->toHeaderValue($idempotency_key);
        }
        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                "{" . "id" . "}",
                $this->apiClient->getSerializer()->toPathValue($id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($body)) {
            $_tempBody = $body;
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('Authorization');
        if (strlen($apiKey) !== 0) {
            $headerParams['Authorization'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge',
                '/charges/{id}/capture'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 402:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 409:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation chargesCreate
     *
     * Create a charge
     *
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CreateChargeRequest $body  (optional)
     * @param string $idempotency_key The unique idempotency key. (optional)
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesCreate($body = null, $idempotency_key = null)
    {
        list($response) = $this->chargesCreateWithHttpInfo($body, $idempotency_key);
        return $response;
    }

    /**
     * Operation chargesCreateWithHttpInfo
     *
     * Create a charge
     *
     * @param \Zip\ZipPayment\MerchantApi\Lib\Model\CreateChargeRequest $body  (optional)
     * @param string $idempotency_key The unique idempotency key. (optional)
     * @return array of \Zip\ZipPayment\MerchantApi\Lib\Model\Charge, HTTP status code, HTTP response headers (array of strings)
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesCreateWithHttpInfo($body = null, $idempotency_key = null)
    {
        // parse inputs
        $resourcePath = "/charges";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/json'));

        // header params
        if ($idempotency_key !== null) {
            $headerParams['Idempotency-Key'] = $this->apiClient->getSerializer()->toHeaderValue($idempotency_key);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        // body params
        $_tempBody = null;
        if (isset($body)) {
            $_tempBody = $body;
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('Authorization');
        if (strlen($apiKey) !== 0) {
            $headerParams['Authorization'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'POST',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge',
                '/charges'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 400:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 401:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 402:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 403:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
                case 409:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ErrorResponse', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }
            $file = 'C:\wamp64\www\magentoce233\var\log\charge.txt';
// Open the file to get existing content
            $current = file_get_contents($file);
            $current .= "Header Data:".json_encode($data);
// Write the contents back to the file
            file_put_contents($file, $current);
            throw $e;
        }
    }

    /**
     * Operation chargesList
     *
     * List charges
     *
     * @param string $state The state filter (optional)
     * @param int $skip Number of items to skip when paging (optional, default to 0)
     * @param int $limit Number of items to retrieve when paging (optional, default to 100)
     * @param string $expand Allows expanding related entities in the response. Only valid entry is &#39;customer&#39; (optional)
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\ChargeCollection
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesList($state = null, $skip = null, $limit = null, $expand = null)
    {
        list($response) = $this->chargesListWithHttpInfo($state, $skip, $limit, $expand);
        return $response;
    }

    /**
     * Operation chargesListWithHttpInfo
     *
     * List charges
     *
     * @param string $state The state filter (optional)
     * @param int $skip Number of items to skip when paging (optional, default to 0)
     * @param int $limit Number of items to retrieve when paging (optional, default to 100)
     * @param string $expand Allows expanding related entities in the response. Only valid entry is &#39;customer&#39; (optional)
     * @return array of \Zip\ZipPayment\MerchantApi\Lib\Model\ChargeCollection, HTTP status code, HTTP response headers (array of strings)
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesListWithHttpInfo($state = null, $skip = null, $limit = null, $expand = null)
    {
        // parse inputs
        $resourcePath = "/charges";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/javascript'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/javascript'));

        // query params
        if ($state !== null) {
            $queryParams['state'] = $this->apiClient->getSerializer()->toQueryValue($state);
        }
        // query params
        if ($skip !== null) {
            $queryParams['skip'] = $this->apiClient->getSerializer()->toQueryValue($skip);
        }
        // query params
        if ($limit !== null) {
            $queryParams['limit'] = $this->apiClient->getSerializer()->toQueryValue($limit);
        }
        // query params
        if ($expand !== null) {
            $queryParams['expand'] = $this->apiClient->getSerializer()->toQueryValue($expand);
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('Authorization');
        if (strlen($apiKey) !== 0) {
            $headerParams['Authorization'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Zip\ZipPayment\MerchantApi\Lib\Model\ChargeCollection',
                '/charges'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Zip\ZipPayment\MerchantApi\Lib\Model\ChargeCollection', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\ChargeCollection', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation chargesRetrieve
     *
     * Retrieve a charge
     *
     * @param string $id The id of the charge (required)
     * @param string $expand Allows expanding related entities in the response. Only valid entry is &#39;customer&#39; (optional)
     * @return \Zip\ZipPayment\MerchantApi\Lib\Model\Charge
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesRetrieve($id, $expand = null)
    {
        list($response) = $this->chargesRetrieveWithHttpInfo($id, $expand);
        return $response;
    }

    /**
     * Operation chargesRetrieveWithHttpInfo
     *
     * Retrieve a charge
     *
     * @param string $id The id of the charge (required)
     * @param string $expand Allows expanding related entities in the response. Only valid entry is &#39;customer&#39; (optional)
     * @return array of \Zip\ZipPayment\MerchantApi\Lib\Model\Charge, HTTP status code, HTTP response headers (array of strings)
     *@throws \Zip\ZipPayment\MerchantApi\Lib\ApiException on non-2xx response
     */
    public function chargesRetrieveWithHttpInfo($id, $expand = null)
    {
        // verify the required parameter 'id' is set
        if ($id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $id when calling chargesRetrieve');
        }
        // parse inputs
        $resourcePath = "/charges/{id}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = $this->apiClient->selectHeaderAccept(array('application/javascript'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = $this->apiClient->selectHeaderContentType(array('application/javascript'));

        // query params
        if ($expand !== null) {
            $queryParams['expand'] = $this->apiClient->getSerializer()->toQueryValue($expand);
        }
        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                "{" . "id" . "}",
                $this->apiClient->getSerializer()->toPathValue($id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        // this endpoint requires API key authentication
        $apiKey = $this->apiClient->getApiKeyWithPrefix('Authorization');
        if (strlen($apiKey) !== 0) {
            $headerParams['Authorization'] = $apiKey;
        }
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath,
                'GET',
                $queryParams,
                $httpBody,
                $headerParams,
                '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge',
                '/charges/{id}'
            );

            return array($this->apiClient->getSerializer()->deserialize($response, '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $httpHeader), $statusCode, $httpHeader);
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Zip\ZipPayment\MerchantApi\Lib\Model\Charge', $e->getResponseHeaders());
                    $e->setResponseObject($data);
                    break;
            }

            throw $e;
        }
    }
}
