<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor;

use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Exception\RepresentProcessorException;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\RepresentationInterface;

/**
 * Class RepresentProcessorInterface.
 */
interface RepresentProcessorInterface
{
    const REPRESENT_TYPE_JSON = 'json';

    /**
     * @param RepresentationInterface $representation
     * @param string                  $className
     * @param string                  $type
     */
    public function addRepresentation(
        RepresentationInterface $representation,
        $className = null,
        $type = self::REPRESENT_TYPE_JSON
    );

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     *
     * @throws RepresentProcessorException
     */
    public function representJSON(PagerDutyEntityInterface $pagerDutyEntity);

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     *
     * @throws RepresentProcessorException
     */
    public function getRESTResourcePath(PagerDutyEntityInterface $pagerDutyEntity);
}
