<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor;

use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Exception\RepresentProcessorException;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\RepresentationInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\RepresentationJSONInterface;
use TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\RepresentationRESTInterface;

/**
 * Class RepresentProcessor.
 */
class RepresentProcessor implements RepresentProcessorInterface
{
    /**
     * @var array
     */
    protected $representation = [];

    /**
     * @param RepresentationInterface $representation
     * @param string                  $className
     * @param string                  $type
     */
    public function addRepresentation(
        RepresentationInterface $representation,
        $className = null,
        $type = self::REPRESENT_TYPE_JSON
    ) {
        if (null === $className) {
            $className = $representation::getEntityClassName();
        }
        $this->representation[$type][$className] = $representation;
    }

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     *
     * @throws RepresentProcessorException
     */
    public function representJson(PagerDutyEntityInterface $pagerDutyEntity)
    {
        /** @var RepresentationJSONInterface $representation */
        $representation = $this->getRepresentation(get_class($pagerDutyEntity), self::REPRESENT_TYPE_JSON);

        return $representation->persistRepresentJSON($pagerDutyEntity);
    }

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     *
     * @throws RepresentProcessorException
     */
    public function getRESTResourcePath(PagerDutyEntityInterface $pagerDutyEntity)
    {
        /** @var RepresentationRESTInterface $representation */
        $representation = $this->getRepresentation(get_class($pagerDutyEntity), self::REPRESENT_TYPE_JSON);

        return $representation->getRESTResourcePath($pagerDutyEntity);
    }

    /**
     * @param string $className
     * @param string $type
     *
     * @return RepresentationInterface
     *
     * @throws RepresentProcessorException
     */
    protected function getRepresentation($className, $type)
    {
        if (!isset($this->representation[$type][$className])) {
            throw RepresentProcessorException::representationDoesNotExist($className, $type);
        }

        return $this->representation[$type][$className];
    }
}
