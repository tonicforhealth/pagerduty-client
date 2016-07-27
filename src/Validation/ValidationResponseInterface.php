<?php

namespace TonicForHealth\PagerDutyClient\Validation;

/**
 * Class ValidationResponseInterface.
 */
interface ValidationResponseInterface
{
    /**
     * @param mixed $data
     *
     * @throws ValidationException
     */
    public function validateResponseData($data);
}
