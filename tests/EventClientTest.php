<?php

namespace TonicHealthCheck\PagerDutyClient\Test;

use Exception;
use Http\Client\Exception as HttpClientException;
use Http\Client\Common\HttpMethodsClient;
use Http\Mock\Client as MockClient;
use Http\Client\Common\PluginClient;
use Http\Discovery\MessageFactoryDiscovery;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use TonicForHealth\PagerDutyClient\Client\EventClient;
use TonicForHealth\PagerDutyClient\Entity\Event\Event;
use TonicForHealth\PagerDutyClient\Entity\Event\EventRepresentation;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessor;
use TonicForHealth\PagerDutyClient\Validation\Event\EventValidationResponse;
use TonicForHealth\PagerDutyClient\Validation\ValidationResponseFactory;
use TonicHealthCheck\PagerDutyClient\Test\Mock\HttpMethodsClientMockException;

/**
 * Class PagerDutyClientTest.
 */
class EventClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RepresentProcessor
     */
    protected $representProcessor;

    /**
     * @var EventRepresentation
     */
    protected $eventRepresentation;

    /**
     * @var MockClient
     */
    protected $mockClient;

    /**
     * @var PluginClient
     */
    protected $pluginClient;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|HttpMethodsClient
     */
    protected $httpMethodsClient;

    /**
     * @var EventClient
     */
    protected $eventClient;

    /**
     * @var EventValidationResponse
     */
    protected $validationResponse;

    /**
     * set up basic mocks.
     */
    public function setUp()
    {
        parent::setUp();

        $this->representProcessor = new RepresentProcessor();

        $this->eventRepresentation = new EventRepresentation();

        $this->representProcessor->addRepresentation($this->eventRepresentation);

        $this->mockClient = new MockClient();

        $this->pluginClient = new PluginClient(
            $this->mockClient
        );

        $this->httpMethodsClient =
            $this
                ->getMockBuilder(HttpMethodsClient::class)
                ->setConstructorArgs(
                    [
                        $this->pluginClient,
                        MessageFactoryDiscovery::find(),
                    ]
                )
                ->enableProxyingToOriginalMethods()
                ->getMock();

        $this->validationResponse = ValidationResponseFactory::createValidation('Event');

        $this->eventClient = new EventClient(
            'https://events.pagerduty.com/generic/2010-04-15',
            $this->httpMethodsClient,
            $this->representProcessor,
            $this->validationResponse

        );
    }

    public function testPostEventSuccess()
    {
        $event = $this->setUpStandardEvent();


        $responseStr = '{
  "status": "success",
  "message": "Event processed",
  "incident_key": "73af7a305bd7012d7c06002500d5d1a6"
}';
        $this->addResponse($responseStr);

        $this->eventClient->post($event);
    }

    /**
     * @expectedException \TonicForHealth\PagerDutyClient\Client\Exception\ResponseDataValidationException
     * @expectedExceptionMessage Event api returned the error:event_type is invalid (must be one of: trigger acknowledge resolve)
     */
    public function testPostEventError()
    {
        $event = $this->setUpStandardEvent();


        $responseStr = '{
    "status": "invalid event",
    "message": "Event object is invalid",
    "errors": [
        "event_type is invalid (must be one of: trigger acknowledge resolve)"
    ]
}';
        $this->addResponse($responseStr);

        $this->eventClient->post($event);
    }

    /**
     * @expectedException \TonicForHealth\PagerDutyClient\Client\Exception\ResponseDataValidationException
     * @expectedExceptionMessage Event api returned the corrupt response data:NULL
     */
    public function testPostEventNoneRepose()
    {
        $event = $this->setUpStandardEvent();


        $responseStr = 'ERROR';
        $this->addResponse($responseStr);

        $this->eventClient->post($event);
    }

    /**
     * @expectedException \TonicForHealth\PagerDutyClient\Client\Exception\EventClientTransportException
     * @expectedExceptionMessage EventClient has a transport problem:Some http transport error
     */
    public function testPostEventTransportException()
    {
        $event = $this->setUpStandardEvent();

        $this->mockClient->addException(
            new HttpMethodsClientMockException("Some http transport error",504)
        );

        $this->eventClient->post($event);
    }

    /**
     * @param $responseStr
     */
    protected function addResponse($responseStr)
    {
        $streamMock = $this->getMockBuilder(StreamInterface::class)->getMock();

        $streamMock
            ->expects($this->once())
            ->method('getContents')
            ->willReturn($responseStr);

        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $response->expects($this->once())->method('getBody')->willReturn($streamMock);

        $this->mockClient->addResponse($response);
    }

    /**
     * @return Event
     */
    private function setUpStandardEvent()
    {
        $this->httpMethodsClient
            ->expects(static::once())
            ->method('post')
            ->with(
                'https://events.pagerduty.com/generic/2010-04-15/create_event.json',
                ['Content-type' => 'application/json'],
                '{
    "service_key": "a2efbd3070e1113c18abef9ea544cd8c",
    "event_type": "trigger",
    "description": "FAILURE for production\/HTTP on machine srv01.acme.com"
}'
            );
        $event = new Event();

        $event->serviceKey = 'a2efbd3070e1113c18abef9ea544cd8c';
        $event->description = 'FAILURE for production/HTTP on machine srv01.acme.com';

        return $event;
    }
}
