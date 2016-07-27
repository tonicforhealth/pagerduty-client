# PagerDuty client
[![License](https://img.shields.io/github/license/tonicforhealth/pagerduty-client.svg?maxAge=2592000)](LICENSE.md)
[![Build Status](https://travis-ci.org/tonicforhealth/pagerduty-client.svg?branch=master)](https://travis-ci.org/tonicforhealth/pagerduty-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d4505168-697d-470c-b297-9b6e26598c37/mini.png)](https://insight.sensiolabs.com/projects/d4505168-697d-470c-b297-9b6e26598c37)


PagerDuty is a client lib that provides us with the object interface to use PagerDuty [events-api](https://v2.developer.pagerduty.com/docs/events-api)
and allows us to extend this lib to use with PagerDuty [rest-api v2](https://v2.developer.pagerduty.com/docs/rest-api)
## Simple way to try it with [Composer](http://getcomposer.org/)
### Installation
if you want to try it
```bash
mkdir pagerduty-client-app && cd pagerduty-client-app && composer init --stability=dev -n &&  composer require tonicforhealth/pagerduty-client php-http/guzzle6-adapter guzzlehttp/guzzle:~6.1
```
## Usage example
```php
<?php

require __DIR__.'/vendor/autoload.php';

use TonicForHealth\PagerDutyClient\Client\EventClientFactory;
use TonicForHealth\PagerDutyClient\Client\Exception\EventClientTransportException;
use TonicForHealth\PagerDutyClient\Client\Exception\ResponseDataValidationException;
use TonicForHealth\PagerDutyClient\Entity\Event\Event;

$eventClient = EventClientFactory::createEventClient(
    'https://events.pagerduty.com/generic/2010-04-15'
);

$event = new Event();

$event->serviceKey = 'change it to your "Integration Key"';
$event->description = 'FAILURE for production/HTTP on machine srv01.acme.com';

try {
    $response = $eventClient->post($event);
} catch (EventClientTransportException $exception) {
    printf('HTTP Transport problem:%s'."\n", $exception->getMessage());
    exit(1);
} catch (ResponseDataValidationException $exception) {
    printf('Validation response problem:%s'."\n", $exception->getMessage());
    exit(2);
}

var_dump($response);
```
## For existing projects with [Composer](http://getcomposer.org/)
### Installation
if you already have a project with composer.json check minimum-stability, and if it is higher than dev, change it to dev
```bash
$ composer config minimum-stability dev
```
then add tonicforhealth/pagerduty-client
```bash
$ composer require tonicforhealth/pagerduty-client
```
also you need to add an [http-client](http://docs.php-http.org/en/latest/clients/curl-client.html#installation) with an adapter, or if you have one, you need to find a [php-http-padapter](https://github.com/php-http?utf8=%E2%9C%93&query=adapter) for it
```bash
$ composer require php-http/guzzle6-adapter guzzlehttp/guzzle:~6.1
```
### Advance Usage example
```php
<?php

require __DIR__.'/vendor/autoload.php';

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use TonicForHealth\PagerDutyClient\Client\EventClient;
use TonicForHealth\PagerDutyClient\Client\Exception\EventClientTransportException;
use TonicForHealth\PagerDutyClient\Client\Exception\ResponseDataValidationException;
use TonicForHealth\PagerDutyClient\Entity\Event\Event;
use TonicForHealth\PagerDutyClient\Entity\Event\EventRepresentation;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessor;
use TonicForHealth\PagerDutyClient\Validation\ValidationResponseFactory;

$representProcessor = new RepresentProcessor();

$eventRepresentation = new EventRepresentation();

$representProcessor->addRepresentation($eventRepresentation);

$validationResponse = ValidationResponseFactory::createValidation('Event');

$httpMethodsClient = new HttpMethodsClient(
    HttpClientDiscovery::find(),
    MessageFactoryDiscovery::find()
);

$eventClient = new EventClient(
    'https://events.pagerduty.com/generic/2010-04-15',
    $httpMethodsClient,
    $representProcessor,
    $validationResponse
);

$event = new Event();

$event->serviceKey = 'change it to your "Integration Key"';
$event->description = 'FAILURE for production/HTTP on machine srv01.acme.com';

try {
    $response = $eventClient->post($event);
} catch (EventClientTransportException $exception) {
    printf('HTTP Transport problem:%s'."\n", $exception->getMessage());
    exit(1);
} catch (ResponseDataValidationException $exception) {
    printf('Validation response problem:%s'."\n", $exception->getMessage());
    exit(2);
}

var_dump($response);

```
