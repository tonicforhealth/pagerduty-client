<?php

namespace TonicHealthCheck\PagerDutyClient\Test;

use Http\Client\Common\HttpMethodsClient;
use Http\Mock\Client as MockClient;
use Http\Client\Common\PluginClient;
use Http\Discovery\MessageFactoryDiscovery;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use TonicForHealth\PagerDutyClient\Client\EventClient;
use TonicForHealth\PagerDutyClient\Entity\Event\Event;
use TonicForHealth\PagerDutyClient\Entity\Event\EventRepresentation;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessor;

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

        $this->eventClient = new EventClient(
            'https://events.pagerduty.com/generic/2010-04-15', $this->httpMethodsClient, $this->representProcessor
        );
    }

    public function testPostEvent()
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

        $this->eventClient->post($event);
    }
}
