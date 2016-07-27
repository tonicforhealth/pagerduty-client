<?php

namespace TonicForHealth\PagerDutyClient\Client;

use Http\Client\Exception as HttpClientException;
use Psr\Http\Message\ResponseInterface;
use stdClass;
use TonicForHealth\PagerDutyClient\Client\Exception\EventClientTransportException;
use TonicForHealth\PagerDutyClient\Client\Exception\ResponseDataValidationException;
use TonicForHealth\PagerDutyClient\Entity\PagerDutyEntityInterface;
use TonicForHealth\PagerDutyClient\Validation\Event\Exception\EventValidationResponseException;

/**
 * Class EventClient.
 */
class EventClient extends AbstractClient
{
    /**
     * Make HTTP POST Request to the Event api.
     *
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return stdClass
     *
     * @throws EventClientTransportException
     * @throws ResponseDataValidationException
     */
    public function post(PagerDutyEntityInterface $pagerDutyEntity)
    {
        $data = null;

        try {
            $response = $this->getHttpClient()->post(
                $this->getResourceUrl($pagerDutyEntity),
                $this->headers,
                $this->getRepresentProcessor()->representJSON($pagerDutyEntity)
            );

            $data = $this->performResponse($response);
        } catch (HttpClientException $exception) {
            throw EventClientTransportException::transportProblem($exception);
        } catch (EventValidationResponseException $exception) {
            throw ResponseDataValidationException::validationFail($exception);
        }

        return $data;
    }

    /**
     * Get Full resource Url.
     *
     * @param PagerDutyEntityInterface $pagerDutyEntity
     *
     * @return string
     */
    private function getResourceUrl(PagerDutyEntityInterface $pagerDutyEntity)
    {
        return sprintf(
            '%s/%s',
            $this->getApiRootUrl(),
            $this->getRepresentProcessor()->getRESTResourcePath($pagerDutyEntity)
        );
    }

    /**
     * Perform response and gets it date.
     *
     * @param mixed $response
     *
     * @return stdClass
     *
     * @throws EventValidationResponseException
     */
    private function performResponse($response)
    {
        $data = false;
        if ($response instanceof ResponseInterface) {
            $data = json_decode($response->getBody()->getContents());
            $this->getValidationResponse()->validateResponseData($data);
        }

        return $data;
    }
}
