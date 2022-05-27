<?php 

namespace Hillus\SinNotifications;

use GuzzleHttp\ClientInterface;
use Illuminate\Mail\Transport\Transport;
use Illuminate\Support\Facades\Log;
use Swift_Mime_SimpleMessage;

class SinNotificationTransport extends Transport
{
    /**
     * Guzzle client instance.
     *
     * @var SinNotificationApiClient
     */
    protected $client;

    /**
     * API key.
     *
     * @var string
     */
    protected $key;

    /**
     * The API URL to which to POST emails.
     *
     * @var string
     */
    protected $url;

    /**
     * Create a new Custom transport instance.
     *
     * @param  SinNotificationApiClient  $client
     * @return void
     */
    public function __construct(SinNotificationApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $data = $this->getPayload($message);

        $this->client->send($data);

        $this->sendPerformed($message);

        return $this->numberOfRecipients($message);
    }

    /**
     * Get the HTTP payload for sending the message.
     *
     * @param  \Swift_Mime_SimpleMessage  $message
     * @return array
     */
    protected function getPayload(Swift_Mime_SimpleMessage $message) : array
    {
        // Change this to the format your API accepts
        $return = [
            'from' => $this->mapContactsToNameEmail($message->getFrom())[0],
            'to' => $this->mapContactsToNameEmail($message->getTo()),
            'cc' => $this->mapContactsToNameEmail($message->getCc()),
            'bcc' => $this->mapContactsToNameEmail($message->getBcc()),
            'message' => $message->getBody(),
            'subject' => $message->getSubject(),
        ];

        // Log::info(json_encode($return));

        return $return;
    }

    protected function mapContactsToNameEmail($contacts)
    {
        $formatted = [];
        if (empty($contacts)) {
            return [];
        }
        foreach ($contacts as $address => $display) {
            $formatted[] =  [
                'name' => $display,
                'email' => $address,
            ];
        }
        return $formatted;
    }

}