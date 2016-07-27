<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor\Representation;

use stdClass;
use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;

/**
 * Interface RepresentationInterface.
 */
interface RepresentationInterface
{
    /**
     * @return string
     */
    public static function getEntityClassName();

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return stdClass
     */
    public function persistRepresentObj(PagerDutyEntityInterface $pagerDutyEntity);

    /**
     * @param stdClass                      $obj
     * @param PagerDutyEntityInterface|null $pagerDutyEntity
     */
    public function loadRepresentObj(stdClass $obj, PagerDutyEntityInterface $pagerDutyEntity = null);
}
