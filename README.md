# PagerDuty client
[![License](https://img.shields.io/github/license/tonicforhealth/pagerduty-client.svg?maxAge=2592000)](LICENSE.md)
[![Build Status](https://travis-ci.org/tonicforhealth/pagerduty-client.svg?branch=master)](https://travis-ci.org/tonicforhealth/pagerduty-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/pagerduty-client/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d4505168-697d-470c-b297-9b6e26598c37/mini.png)](https://insight.sensiolabs.com/projects/d4505168-697d-470c-b297-9b6e26598c37)


PagerDuty a client lib that provides us the object interface to use PagerDuty [Events-api](https://v2.developer.pagerduty.com/docs/events-api)
and lets us to extend this lib to use with PagerDuty [Rest-api v2](https://v2.developer.pagerduty.com/docs/rest-api)
## Installation using [Composer](http://getcomposer.org/)

```bash
$ composer require tonicforhealth/pagerduty-client
```

## Usage
### Simple usage example:
```php
<?php
require __DIR__.'/vendor/autoload.php';

use TonicForHealth\PagerDutyClient\Client\EventClient;
use TonicForHealth\PagerDutyClient\Entity\Event\Event;

$eventClient = new EventClient(
    'https://events.pagerduty.com/generic/2010-04-15'
);

$event = new Event();

$event->serviceKey = 'change it to your "Integration Key"';
$event->description = 'FAILURE for production/HTTP on machine srv01.acme.com';

$response = $eventClient->post($event);

var_dump($response);
```
### Advance usage example:
```php
<?php
require __DIR__.'/vendor/autoload.php';

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use TonicForHealth\PagerDutyClient\Client\EventClient;
use TonicForHealth\PagerDutyClient\Entity\Event\Event;
use TonicForHealth\PagerDutyClient\Entity\Event\EventRepresentation;
use TonicForHealth\PagerDutyClient\RepresentProcessor\RepresentProcessor;

$representProcessor = new RepresentProcessor();

$eventRepresentation = new EventRepresentation();

$representProcessor->addRepresentation($eventRepresentation);

$httpMethodsClient = new HttpMethodsClient(
    HttpClientDiscovery::find(),
    MessageFactoryDiscovery::find()
);

$eventClient = new EventClient(
    $httpMethodsClient,
    'https://events.pagerduty.com/generic/2010-04-15',
    $representProcessor
);

$event = new Event();

$event->serviceKey = 'change it to your "Integration Key"';
$event->description = 'FAILURE for production/HTTP on machine srv01.acme.com';

$response = $eventClient->post($event);

var_dump($response);

```
