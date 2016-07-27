<?php

namespace TonicForHealth\PagerDutyClient\Entity\Event;

use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\AbstractRepresentation;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\RepresentationJSONInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\RepresentationRESTInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\Traits\JSONRepresentationTrait;

/**
 * Class EventRepresentation.
 */
class EventRepresentation extends AbstractRepresentation implements RepresentationJSONInterface, RepresentationRESTInterface
{
    use JSONRepresentationTrait;

    /**
     * @param string                        $method
     * @param PagerDutyEntityInterface|null $pagerDutyEntity
     *
     * @return string
     */
    public function getRESTResourcePath($method = 'post', PagerDutyEntityInterface $pagerDutyEntity = null)
    {
        return 'create_event.json';
    }
}
