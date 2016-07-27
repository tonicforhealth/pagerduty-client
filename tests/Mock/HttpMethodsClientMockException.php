<?php

namespace TonicHealthCheck\PagerDutyClient\Test\Mock;

use Exception;
use Http\Client\Exception as HttpClientException;
/**
 * Class HttpMethodsClientMockException
 */
class HttpMethodsClientMockException extends Exception implements HttpClientException
{

}
