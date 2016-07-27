<?php

namespace TonicForHealth\PagerDutyClient\Validation\Event\Exception;

/**
 * Class EventValidationResponseErrorException.
 */
class EventValidationResponseErrorException extends EventValidationResponseException
{
    /**
     * @param string $error
     *
     * @return EventValidationResponseErrorException
     */
    public static function eventApiResponseError($error)
    {
        return new self(
            sprintf(
                'Event api returned the error:%s',
                $error
            )
        );
    }
}
