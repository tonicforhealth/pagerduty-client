<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor\Representation;

use stdClass;
use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;

/**
 * Class AbstractRepresentation.
 */
abstract class AbstractRepresentation implements RepresentationInterface
{
    /**
     * @return string
     */
    public static function getEntityClassName()
    {
        return preg_replace('#Representation$#', '', static::class);
    }

    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return stdClass
     */
    public function persistRepresentObj(PagerDutyEntityInterface $pagerDutyEntity)
    {
        $representObject = new stdClass();

        $objArray = (array) $pagerDutyEntity;
        foreach ($objArray as $key => $value) {
            $representObject->$key = $value;
        }

        return $representObject;
    }

    /**
     * @param stdClass                      $obj
     * @param PagerDutyEntityInterface|null $pagerDutyEntity
     */
    public function loadRepresentObj(stdClass $obj, PagerDutyEntityInterface $pagerDutyEntity = null)
    {
        if (null === $pagerDutyEntity) {
            $entityClassName = static::getEntityClassName();
            $pagerDutyEntity = new $entityClassName();
        }
        $objArray = (array) $obj;
        foreach ($objArray as $key => $value) {
            echo $representObject->$key = $value;
        }
    }
}
