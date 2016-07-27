<?php

namespace TonicForHealth\PagerDutyClient\Validation\Event\Exception;

/**
 * Class EventValidationResponseCorruptDataException.
 */
class EventValidationResponseCorruptDataException extends EventValidationResponseException
{
    /**
     * @param mixed $data
     *
     * @return EventValidationResponseCorruptDataException
     */
    public static function eventApiResponseCorrupt($data)
    {
        return new self(
            sprintf(
                'Event api returned the corrupt response data:%s',
                var_export($data, true)
            )
        );
    }
}
