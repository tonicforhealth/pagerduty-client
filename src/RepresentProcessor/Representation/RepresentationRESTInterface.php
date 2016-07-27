<?php

namespace TonicForHealth\PagerDutyClient\RepresentProcessor\Representation;

use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;

/**
 * Interface RepresentationRESTInterface.
 */
interface RepresentationRESTInterface
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    /**
     * @param string                        $method
     * @param PagerDutyEntityInterface|null $pagerDutyEntity
     *
     * @return string
     */
    public function getRESTResourcePath($method = 'post', PagerDutyEntityInterface $pagerDutyEntity = null);
}
