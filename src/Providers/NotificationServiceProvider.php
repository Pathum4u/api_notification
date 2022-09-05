<?php

namespace Pathum4u\ApiNotification\Providers;

class NotificationServiceProvider
{

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $secret;

    public function __construct()
    {
        $this->baseUri = config('mail.base_uri');
        $this->secret = config('mail.secret');
    }

}
