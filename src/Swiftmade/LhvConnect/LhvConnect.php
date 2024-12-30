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
    private $configuration;

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

    /**
     * Test request. Tests the connection to the server.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function makeHeartbeatGetRequest()
    {
        return $this->client->get('/heartbeat');
    }

    /**
     * Retrieve all the messages from the inbox
     * Deletes all the retrieved messages from the inbox.
     *
     * @return array
     */
    public function getAllMessages()
    {
        $messages = [];

        while (true) {
            $message = $this->makeRetrieveMessageFromInboxRequest();

            if (! isset($message->getHeaders()['Content-Length']) || $message->getHeader('Content-Length')[0] == 0) {
                break;
            }

            $this->makeDeleteMessageInInboxRequest($message);

            array_push($messages, $message);
        }

        return $messages;
    }

    /**
     * @return ResponseInterface
     */
    public function makeRetrieveMessageFromInboxRequest()
    {
        $request = new RetrieveMessageFromInbox($this->client, $this->configuration);

        return $request->sendRequest();
    }

    /**
     * @param ResponseInterface $message
     *
     * @return ResponseInterface
     */
    public function makeDeleteMessageInInboxRequest(ResponseInterface $message)
    {
        $id = $message->getHeader('Message-Response-Id')[0];
        $request = new DeleteMessageInInbox(
            $this->client,
            $this->configuration,
            null,
            [],
            $id
        );

        return $request->sendRequest();
    }

    public function makeGetAccountStatementRequest()
    {
        $this->client->request('POST', '/account-statement');
    }

    /**
     * @param $payments
     *
     * @return string
     */
    public function getPaymentInitiationXML($payments)
    {
        $this->client->post('/payment-initiation', [
            'form_params' => $payments,
        ]);
    }

    /**
     * @param $ddoc
     *
     * @return ResponseInterface
     */
    public function makePaymentInitiationRequest($ddoc)
    {
        $body = fopen($ddoc, 'r');

        $headers = [
            'Content-Type' => 'application/vnd.etsi.asic-e+zip',
        ];

        $request = new PaymentInitiationRequest(
            $this->client,
            $this->configuration,
            [],
            $body,
            $headers
        );

        return $request->sendRequest();
    }
}
