<?php

namespace TonicForHealth\PagerDutyClient\Entity\Event;

use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;

/**
 * Class Event.
 */
class Event implements PagerDutyEntityInterface
{
    const EVENT_TYPE_TRIGGER = 'trigger';
    const EVENT_TYPE_ACKNOWLEDGE = 'acknowledge';
    const EVENT_TYPE_RESOLVE = 'resolve';

    public $serviceKey;
    public $eventType = self::EVENT_TYPE_TRIGGER;
    public $description;
    public $incidentKey;
    public $client;
    public $clientUrl;
    public $details;
    public $contexts;
}
