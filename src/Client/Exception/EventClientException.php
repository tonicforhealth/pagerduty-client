<?php

namespace TonicForHealth\PagerDutyClient\Client\Exception;

use Exception;
use Http\Client\Exception as HttpClientException;

/**
 * Class EventClientException.
 */
class EventClientException extends Exception
{
    /**
     * @param HttpClientException $exception
     *
     * @return static
     */
    public static function internalProblem(HttpClientException $exception)
    {
        return new self($exception->getMessage(), $exception->getCode(), $exception);
    }
}
