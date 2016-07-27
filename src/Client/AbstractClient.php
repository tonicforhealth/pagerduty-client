<?php

namespace TonicForHealth\PagerDutyClient\Client;

use Http\Client\Common\HttpMethodsClient;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessorInterface;
use TonicForHealth\PagerDutyClient\Validation\ValidationResponseInterface;

/**
 * Class AbstractClient.
 */
abstract class AbstractClient
{
    /**
     * @var array
     */
    protected $headers = ['Content-type' => 'application/json'];
    /**
     * @var HttpMethodsClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiRootUrl;

    /**
     * @var RepresentProcessorInterface
     */
    protected $representProcessor;

    /**
     * @var ValidationResponseInterface
     */
    protected $validationResponse;

    /**
     * RequestNotificationType constructor.
     *
     * @param string                      $apiRootUrl
     * @param HttpMethodsClient           $httpClient
     * @param RepresentProcessorInterface $representProcessor
     * @param ValidationResponseInterface $validationResponse
     */
    public function __construct(
        $apiRootUrl,
        HttpMethodsClient $httpClient,
        RepresentProcessorInterface $representProcessor,
        ValidationResponseInterface $validationResponse
    ) {
        $this->setApiRootUrl($apiRootUrl);

        $this->setHttpClient($httpClient);

        $this->setRepresentProcessor($representProcessor);

        $this->setValidationResponse($validationResponse);
    }

    /**
     * @return HttpMethodsClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return string
     */
    public function getApiRootUrl()
    {
        return $this->apiRootUrl;
    }

    /**
     * @return RepresentProcessorInterface
     */
    public function getRepresentProcessor()
    {
        return $this->representProcessor;
    }

    /**
     * @return ValidationResponseInterface
     */
    public function getValidationResponse()
    {
        return $this->validationResponse;
    }

    /**
     * @param HttpMethodsClient $httpClient
     */
    protected function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $apiRootUrl
     */
    protected function setApiRootUrl($apiRootUrl)
    {
        $this->apiRootUrl = $apiRootUrl;
    }

    /**
     * @param RepresentProcessorInterface $representProcessor
     */
    protected function setRepresentProcessor($representProcessor)
    {
        $this->representProcessor = $representProcessor;
    }

    /**
     * @param ValidationResponseInterface $validationResponse
     */
    protected function setValidationResponse($validationResponse)
    {
        $this->validationResponse = $validationResponse;
    }
}
