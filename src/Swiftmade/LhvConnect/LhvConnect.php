<?php

namespace Swiftmade\LhvConnect;

use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Swiftmade\LhvConnect\Requests\DeleteMessageInInbox;
use Swiftmade\LhvConnect\Requests\PaymentInitiationRequest;
use Swiftmade\LhvConnect\Requests\RetrieveMessageFromInbox;

class LhvConnect
{
    private $client;

    public function __construct(array $configuration)
    {
        $this->client = new LhvConnectClient($configuration);
    }

    /**
     * account is the key of the account in the configuration file.
     * For example, 'sandbox' or 'live'.
     */
    public static function make(string $account)
    {
        if (! Arr::has(config('lhv-connect.accounts'), $account)) {
            throw new \Exception(
                "$account is not a valid account in the configuration file. "
                    . "Possible values are: "
                    . implode(', ', array_keys(config('lhv-connect.accounts')))
            );
        }

        return new self(config('lhv-connect.accounts')[$account]);
    }

    public function sendHeartbeat()
    {
        return $this->client->get('/heartbeat');
    }

    public function accountStatement()
    {
        return $this->client->post('/account-statement');
    }

    public function accountBalance()
    {
        return $this->client->get('/account-balance');
    }

    public function getNextMessage()
    {
        return $this->client->get('/messages/next');
    }

    public function deleteMessage($id)
    {
        return $this->client->delete('/messages/' . $id);
    }

    public function getAllMessages()
    {
        $messages = [];

        while (true) {
            $message = $this->getNextMessage();

            if (! isset($message->getHeaders()['Content-Length']) || $message->getHeader('Content-Length')[0] == 0) {
                break;
            }

            $this->client->deleteMessage($message->getHeader('Message-Response-Id')[0]);

            array_push(
                $messages,
                $message->getBody()
            );
        }

        return $messages;
    }
}
