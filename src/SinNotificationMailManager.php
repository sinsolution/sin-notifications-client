<?php 

namespace Hillus\SinNotifications;

use Illuminate\Mail\MailManager;

class SinNotificationMailManager extends MailManager
{
    protected function createSinnotificationTransport()
    {
        $config = $this->app['config']->get('sinnotification', []);

        return new SinNotificationTransport(
            new SinNotificationApiClient($this->guzzle([]),$config)
        );
    }
}

