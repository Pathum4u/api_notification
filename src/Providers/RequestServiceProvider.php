<?php

namespace Pathum4u\ApiNotification\Providers;

use GuzzleHttp\Client;
use pathum4u\send_api_notification\Providers\NotificationServiceProvider;

class RequestServiceProvider extends NotificationServiceProvider
{

    /**
     * @param       $method
     * @param       $requestUrl
     * @param array $formParams
     * @param array $headers
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $requestUrl, $formParams = [], $headers = [])
    {

        //
        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
            $headers['Accept'] = 'application/json';
            $headers['Content-Type'] = 'application/json';
        }

        $response = $client->request(
            $method,
            $requestUrl,
            [
                'json' => $formParams,
                'headers' => $headers
            ]
        );

        return $response->getBody()->getContents();
    }
}
