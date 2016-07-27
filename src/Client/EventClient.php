<?php

namespace TonicForHealth\PagerDutyClient\Client;

use Http\Client\Exception as HttpClientException;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use TonicForHealth\PagerDutyClient\Client\Exception\EventApiResponseCorruptException;
use TonicForHealth\PagerDutyClient\Client\Exception\EventApiResponseErrorException;
use TonicForHealth\PagerDutyClient\Client\Exception\EventClientException;
use TonicForHealth\PagerDutyClient\Entity\Event\EventRepresentation;
use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessor;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessorInterface;

/**
 * Class EventClient.
 */
class EventClient
{
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
     * RequestNotificationType constructor.
     *
     * @param string                      $apiRootUrl
     * @param HttpMethodsClient           $httpClient
     * @param RepresentProcessorInterface $representProcessor
     */
    public function __construct(
        $apiRootUrl,
        HttpMethodsClient $httpClient = null,
        RepresentProcessorInterface $representProcessor = null
    ) {
        $this->setApiRootUrl($apiRootUrl);

        if (null === $httpClient) {
            $httpClient = $this->createHttpClient();
        }
        $this->setHttpClient($httpClient);

        if (null === $representProcessor) {
            $representProcessor = $this->createRepresentProcessor();
        }
        $this->setRepresentProcessor($representProcessor);

        $this->addBasicRepresentation();
    }

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return ResponseInterface
     *
     * @throws EventApiResponseErrorException
     */
    public function post(PagerDutyEntityInterface $pagerDutyEntity)
    {
        $data = null;

        try {
            $response = $this->getHttpClient()->post(
                $this->getResourceUrl($pagerDutyEntity),
                $this->headers,
                $this->getRepresentProcessor()->representJSON($pagerDutyEntity)
            );

            $data = $this->performResponse($response);
        } catch (HttpClientException $exception) {
            EventClientException::internalProblem($exception);
        }

        return $data;
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
     * add basic representation for EventClient.
     */
    protected function addBasicRepresentation()
    {
        $this->getRepresentProcessor()->addRepresentation(new EventRepresentation());
    }

    /**
     * @return HttpMethodsClient
     */
    protected function createHttpClient()
    {
        $httpClient = new HttpMethodsClient(
            HttpClientDiscovery::find(),
            MessageFactoryDiscovery::find()
        );

        return $httpClient;
    }

    /**
     * @return RepresentProcessor
     */
    protected function createRepresentProcessor()
    {
        return new RepresentProcessor();
    }

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     */
    private function getResourceUrl(PagerDutyEntityInterface $pagerDutyEntity)
    {
        return sprintf(
            '%s/%s',
            $this->getApiRootUrl(),
            $this->getRepresentProcessor()->getRESTResourcePath($pagerDutyEntity)
        );
    }

    /**
     * @param $response
     *
     * @return bool|\stdClass
     *
     * @throws EventApiResponseErrorException
     */
    private function performResponse($response)
    {
        $data = false;
        if ($response instanceof ResponseInterface) {
            $data = json_decode($response->getBody()->getContents());
            self::validateResponseData($data);
        }

        return $data;
    }

    /**
     * @param mixed $data
     *
     * @throws EventClientException
     */
    private static function validateResponseData($data)
    {
        if (!isset($data->status)) {
            throw EventApiResponseCorruptException::eventApiResponseCorrupt($data);
        } elseif (isset($data->errors)) {
            throw EventApiResponseErrorException::eventApiResponseError($data->errors[0]);
        }
    }
}
