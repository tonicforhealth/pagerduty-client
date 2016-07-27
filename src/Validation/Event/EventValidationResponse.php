<?php

namespace TonicForHealth\PagerDutyClient\Validation\Event;

use TonicForHealth\PagerDutyClient\Validation\Event\Exception\EventValidationResponseCorruptDataException;
use TonicForHealth\PagerDutyClient\Validation\Event\Exception\EventValidationResponseErrorException;
use TonicForHealth\PagerDutyClient\Validation\ValidationResponseInterface;

/**
 * Class EventValidationResponse.
 */
class EventValidationResponse implements ValidationResponseInterface
{
    /**
     * @param mixed $data
     *
     * @throws EventValidationResponseCorruptDataException
     * @throws EventValidationResponseErrorException
     */
    public function validateResponseData($data)
    {
        if (!isset($data->status)) {
            throw EventValidationResponseCorruptDataException::eventApiResponseCorrupt($data);
        } elseif (isset($data->errors)) {
            throw EventValidationResponseErrorException::eventApiResponseError($data->errors[0]);
        }
    }
}
