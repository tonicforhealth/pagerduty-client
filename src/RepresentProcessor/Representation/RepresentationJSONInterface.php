<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor\Representation;

use stdClass;
use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;

/**
 * Interface RepresentationJSONInterface.
 */
interface RepresentationJSONInterface
{
    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     */
    public function persistRepresentJSON(PagerDutyEntityInterface $pagerDutyEntity);

    /**
     * @param stdClass|string               $jsonString
     * @param PagerDutyEntityInterface|null $pagerDutyEntity
     *
     * @return PagerDutyEntityInterface
     */
    public function loadRepresentJSON($jsonString, PagerDutyEntityInterface $pagerDutyEntity = null);
}
