<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor\Exception;

use Exception;

/**
 * Class RepresentProcessorException.
 */
class RepresentProcessorException extends Exception
{
    /**
     * @param string $className
     * @param string $type
     *
     * @return self
     */
    public static function representationDoesNotExist($className, $type)
    {
        return new self(
            sprintf(
                'Representation doesn\'t exist for CLASSNAME:%s and TYPE:%s',
                $className,
                $type
            )
        );
    }
}
