<?php

namespace Hillus\SinNotifications;

use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Log;

class SinNotificationMailProvider extends MailServiceProvider
{

    public function boot() 
    { 
    $this->publishes([
            __DIR__.'/config/sinnotification.php' => config_path('sinnotification.php')
        ], 'sinnotification');
    }
 
   
    /**
     * Register the Illuminate mailer instance.
     *
     * @return void
     */
    protected function registerIlluminateMailer()
    {
        Log::info(__METHOD__ . ' :: start');
        if ($this->app['config']['mail.driver'] == 'sinnotification') 
        {
            Log::info('sinnotification');
            $this->registerSinNotificationMailer();
        }else{
            Log::info('swifft');
            return parent::registerIlluminateMailer();
        }
        Log::info(__METHOD__ . ' :: end');
        
    }



    private function registerSinNotificationMailer()
    {
        $this->app->singleton('mail.manager', function($app) {
            return new SinNotificationMailManager($app);
        });

        // Copied from Illuminate\Mail\MailServiceProvider
        $this->app->bind('mailer', function ($app) {
            return $app->make('mail.manager')->mailer();
        });
    }

}
