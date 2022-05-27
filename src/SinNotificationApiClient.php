<?php 

namespace Hillus\SinNotifications;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Swift_Mime_SimpleMessage;

class SinNotificationApiClient
{

    /**
     * client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;


    /**
     * Config.
     *
     * @var array
     */
    protected $config;


     /**
     * base_url.
     *
     * @var string
     */
    protected $base_url;

     /**
     * email.
     *
     * @var string
     */
    protected $email;


    /**
     * password.
     *
     * @var string
     */
    protected $password;


    /**
     * access_token.
     *
     * @var string
     */
    protected $access_token;

    /**
     * Create a new Custom transport instance.
     *
     * @param  \GuzzleHttp\ClientInterface  $client
     * @param  array  $config
     * @return void
     */
    public function __construct(ClientInterface  $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
        $this->base_url = $config['base_url'];
        $this->email = $config['email'];
        $this->password = $config['password'];

    }

        /**
     * {@inheritdoc}
     */
    public function login()
    {
        $key_cache = 'sin_notification_access_token';
        if(Cache::has($key_cache ))
        {
            $this->access_token = Cache::get($key_cache);
            return;

        }
        $data = [
            'email' => $this->email,
            'password' => $this->password
        ];
        $payload = $this->getPayload($data );

        dump($payload);

        $url = $this->base_url.'/api/auth/login';
        
        dump($url);
        try
        {
            $response = $this->client->request('POST', $url, $payload);
            $content = json_decode($response->getBody()->getContents());
            $this->access_token = $content->access_token;

            Cache::put($key_cache , $this->access_token, $content->expires_in);
        }
        catch(\GuzzleHttp\Exception\ClientException $e)
        {
            Cache::forget($key_cache);
            $res = $e->getResponse(); 
            dd($res->getStatusCode()." = ".$res->getBody());
        }

    }

    /**
     * {@inheritdoc}
     */
    public function send(array $data)
    {
        $this->login();
        $payload = $this->getPayload($data);
        $url = $this->base_url.'/api/email';

        $res = $this->client->request('POST', $url, $payload);

        dump($res->getBody()->getContents());
    }

    /**
     * Get the HTTP payload for sending the message.
     *
     * @param  \Swift_Mime_SimpleMessage  $message
     * @return array
     */
    protected function getPayload(array $data)
    {
        // Change this to the format your API accepts
        $return = [
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
            'json' => $data,
            // 'debug' => true
        ];

        if($this->access_token){
            $return['headers']['Authorization'] = 'Bearer ' . $this->access_token;
        }

        Log::info(json_encode($return));

        return $return;
    }



}