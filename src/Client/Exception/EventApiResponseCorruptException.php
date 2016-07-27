<?php

namespace TonicForHealth\PagerDutyClient\Client\Exception;

/**
 * Class RepresentProcessorException.
 */
class EventApiResponseCorruptException extends EventClientException
{
    /**
     * @param mixed $data
     *
     * @return self
     */
    public static function eventApiResponseCorrupt($data)
    {
        return new self(
            sprintf(
                'Event api returned the currupt response data:%s',
                var_export($data, true)
            )
        );
    }
}
