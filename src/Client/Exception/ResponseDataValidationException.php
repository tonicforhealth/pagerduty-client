<?php

namespace TonicForHealth\PagerDutyClient\Client\Exception;

use TonicForHealth\PagerDutyClient\Validation\Event\Exception\EventValidationResponseException;

/**
 * Class ResponseDataValidationException.
 */
class ResponseDataValidationException extends EventClientException
{
    /**
     * @param EventValidationResponseException $exception
     *
     * @return static
     */
    public static function validationFail(EventValidationResponseException $exception)
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
