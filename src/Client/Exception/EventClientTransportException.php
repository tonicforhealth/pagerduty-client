<?php

namespace TonicForHealth\PagerDutyClient\Client\Exception;

use Http\Client\Exception as HttpClientException;

/**
 * Class EventClientTransportProblem.
 */
class EventClientTransportException extends EventClientException
{
    /**
     * @param HttpClientException $exception
     *
     * @return static
     */
    public static function transportProblem(HttpClientException $exception)
    {
        return new self(
            sprintf(
                'EventClient has a transport problem:%s',
                $exception->getMessage()
            ),
            $exception->getCode(),
            $exception
        );
    }
}
