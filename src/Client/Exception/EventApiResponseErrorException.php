<?php

namespace TonicForHealth\PagerDutyClient\Client\Exception;

/**
 * Class RepresentProcessorException.
 */
class EventApiResponseErrorException extends EventClientException
{
    /**
     * @param string $error
     *
     * @return self
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
