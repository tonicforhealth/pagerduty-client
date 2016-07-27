<?php

namespace TonicForHealth\PagerDutyClient\Client;

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use TonicForHealth\PagerDutyClient\Client\Exception\EventClientFactoryCreateException;
use TonicForHealth\PagerDutyClient\Entity\Event\EventRepresentation;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessor;
use TonicForHealth\PagerDutyClient\Validation\ValidationResponseFactory;

/**
 * Class EventClientFactory.
 */
class EventClientFactory
{
    /**
     * @param string $apiRootUrl
     *
     * @return EventClient
     *
     * @throws EventClientFactoryCreateException
     */
    public static function createEventClient($apiRootUrl)
    {
        $httpClient = self::createHttpClient();

        $representProcessor = self::createRepresentProcessor();
        self::addBasicRepresentations($representProcessor);
        $validationResponse = ValidationResponseFactory::createValidation('Event');

        $eventClient = new EventClient(
            $apiRootUrl,
            $httpClient,
            $representProcessor,
            $validationResponse
        );

        return $eventClient;
    }

    /**
     * @return HttpMethodsClient
     *
     * @throws EventClientFactoryCreateException
     */
    protected static function createHttpClient()
    {
        try {
            $httpClient = new HttpMethodsClient(
                HttpClientDiscovery::find(),
                MessageFactoryDiscovery::find()
            );
        } catch (NotFoundException $exception) {
            throw new EventClientFactoryCreateException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $httpClient;
    }

    /**
     * @return RepresentProcessor
     */
    protected static function createRepresentProcessor()
    {
        return new RepresentProcessor();
    }

    /**
     * add basic representations for EventClient.
     *
     * @param RepresentProcessor $representProcessor
     */
    protected static function addBasicRepresentations(RepresentProcessor $representProcessor)
    {
        $representProcessor->addRepresentation(new EventRepresentation());
    }
}
