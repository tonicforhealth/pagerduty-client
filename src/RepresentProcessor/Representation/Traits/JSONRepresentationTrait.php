<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor\Representation\Traits;

use stdClass;
use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;

/**
 * Trait JSONRepresentationTrait.
 */
trait JSONRepresentationTrait
{
    /**
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     */
    public function persistRepresentJSON(PagerDutyEntityInterface $pagerDutyEntity)
    {
        return json_encode(
           $this->persistRepresentObj($pagerDutyEntity),
           JSON_PRETTY_PRINT
       );
    }

    /**
     * @param string                        $jsonString
     * @param PagerDutyEntityInterface|null $pagerDutyEntity
     *
     * @return PagerDutyEntityInterface
     */
    public function loadRepresentJSON($jsonString, PagerDutyEntityInterface $pagerDutyEntity = null)
    {
        return $this->loadRepresentObj(
            json_decode($jsonString)
        );
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
            if (null === $value) {
                continue;
            }
            $key = $this->cameCaseTransformToUnderscores($key);
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
            $key = $this->underscoresTransformToCameCase($key);
            $representObject->$key = $value;
        }
    }

    /**
     * Decamelize str cameCase string to underscored.
     *
     * @param string $cameCaseStr
     *
     * @return string
     */
    protected function cameCaseTransformToUnderscores($cameCaseStr)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $cameCaseStr)), '_');
    }

    /**
     * Decamelize str cameCase string to underscored.
     *
     * @param string $underscores
     *
     * @return string
     */
    protected function underscoresTransformToCameCase($underscores)
    {
        return ltrim(
            preg_replace_callback(
                '/_[a-z]/',
                function ($matches) {
                    return substr(strtolower($matches[0]), 1);
                },
                $underscores
            ),
            '_'
        );
    }
}
