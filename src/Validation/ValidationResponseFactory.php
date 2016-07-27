<?php

namespace TonicForHealth\PagerDutyClient\Validation;

/**
 * Class ValidationFactory.
 */
class ValidationResponseFactory
{
    /**
     * @param string $resourceName
     *
     * @return ValidationResponseInterface
     */
    public static function createValidation($resourceName)
    {
        $className = sprintf(
            '\%s\%s\%sValidationResponse',
            __NAMESPACE__,
            $resourceName,
            $resourceName
        );

        return new $className();
    }
}
