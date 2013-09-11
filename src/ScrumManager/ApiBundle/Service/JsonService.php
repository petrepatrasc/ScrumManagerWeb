<?php
/**
 * Created by JetBrains PhpStorm.
 * User: petre
 * Date: 9/13/13
 * Time: 7:06 AM
 * To change this template use File | Settings | File Templates.
 */

namespace ScrumManager\ApiBundle\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class JsonService {

    /**
     * @var \Symfony\Component\Serializer\Encoder\JsonEncoder
     */
    protected $json;

    /**
     * JSON Service constructor.
     */
    public function __construct() {
        $this->json = new JsonEncoder();
    }

    /**
     * Decode a JSON request into an array.
     * @param string $request The JSON request.
     * @param string $format The format in which data should be decoded.
     * @return array The PHP array that was made after decoding the data.
     */
    public function decode($request = null, $format = 'json') {
        return $this->json->decode($request, $format);
    }

    /**
     * Encode an array into a JSON.
     * @param array $params The parameters that should be encoded into JSON.
     * @param string $format The format of the encoding.
     * @return string The encoded JSON string.
     */
    public function encode(array $params = array(), $format = 'json') {
        return $this->json->encode($params, $format);
    }

    /**
     * Return a successful JSON response.
     * @param array $params The parameters that should be encoded.
     * @param string $format The format for encoding the parameters.
     * @return JsonResponse The JSON response for the successful response.
     */
    public function sucessResponse(array $params = array(), $format = 'json') {
        $responseData = array (
            'status' => 100,
            'message' => 'Request was successful'
        );
        $params = array_merge($params, $responseData);

        return new JsonResponse($params);
    }

    /**
     * Return an unsuccessful JSON response.
     * @param \Exception $exception Manipulator for status messages.
     * @param string $format The format in which data should be encoded.
     * @return JsonResponse The JSON response for the unsuccessful request.
     */
    public function errorResponse(\Exception $exception = null, $format = 'json') {
        $responseData = array(
            'status' => 103,
            'message' => 'Error in request'
        );

        $params = array();
        if (!is_null($exception)) {
            $params['status'] = $exception->getCode();
            $params['message'] = $exception->getMessage();
        }
        $responseData = array_merge($responseData, $params);

        return new JsonResponse($responseData);
    }
}