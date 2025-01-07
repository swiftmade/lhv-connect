<?php

namespace Swiftmade\LhvConnect;

use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Swiftmade\LhvConnect\Requests\AbstractRequest;
use Swiftmade\LhvConnect\Requests\AccountStatementRequest;

class LhvConnect
{
    private $client;

    public function __construct(private array $configuration)
    {
        $this->client = new LhvConnectClient($configuration);
    }

    /**
     * account is the key of the account in the configuration file.
     * For example, 'sandbox' or 'live'.
     */
    public static function make(string $account)
    {
        if (! Arr::has(Config::get('lhv-connect.accounts'), $account)) {
            throw new \Exception(
                "$account is not a valid account in the configuration file. "
                    . "Possible values are: "
                    . implode(', ', array_keys(Config::get('lhv-connect.accounts')))
            );
        }

        return new self(Config::get('lhv-connect.accounts')[$account]);
    }

    public function sendHeartbeat()
    {
        return $this->client->get('/heartbeat');
    }

    public function getAccountStatement(DateTime $fromDate, DateTime $toDate, string $accountIban = null)
    {
        if (is_null($accountIban)) {
            $accountIban = $this->configuration['IBAN'];
        }

        if (empty($accountIban)) {
            throw new \Exception('Account IBAN is required either in the method or in the configuration file.');
        }

        $request = new AccountStatementRequest(
            $accountIban,
            $fromDate,
            $toDate
        );

        return $this->runRequest($request);
    }

    public function accountBalance()
    {
        return $this->client->get('/account-balance');
    }


    public function deleteMessage($id)
    {
        $response = $this->client->delete('/messages/' . $id);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException(
                'Unexpected status code. Expected 200, got ' . $response->getStatusCode() . ' instead.'
            );
        }
    }

    public function getAllMessages()
    {
        return $this->client->get('/messages')->getBody()->getContents();
    }

    /**
     * Send the request to the LHV Connect API.
     * This method is blocking and will wait for the response.
     */
    public function runRequest(AbstractRequest $request)
    {
        /**
         * Since LHV Connect API returns responses asynchronously,
         * We use a lock to make sure we're the only one making a request at a time.
         * 
         * This is a simplistic approach and may not be suitable for high traffic applications.
         */
        return Cache::lock('lhv-connect-request', 60)->block(2, function () use ($request) {

            $response = $this->client->post($request->endpoint(), [
                'body' => $request->toXml()
            ]);

            if ($response->getStatusCode() !== 202) {
                throw new RuntimeException(
                    'Unexpected status code. Expected 202, got ' . $response->getStatusCode() . ' instead.'
                );
            }

            $sentRequestId = $response->getHeader('Message-Request-Id')[0];

            if (! $sentRequestId) {
                throw new RuntimeException('Message-Request-Id header is missing in the response.');
            }

            $retries = 0;
            $maxRetries = 10;

            while (true) {
                if (++$retries === $maxRetries) {
                    throw new RuntimeException('Max retries reached. No matching response found.');
                }

                // Exponential backoff
                usleep((2 ** $retries) * 100_000);

                Log::debug('Checking for the next message. Retries: ' . $retries);

                $messageResponse = $this->client->get('/messages/next', [
                    'headers' => [
                        'Filter-Response-Type' => $request->responseType()
                    ]
                ]);

                if ($messageResponse->getStatusCode() !== 200) {
                    throw new RuntimeException(
                        'Unexpected status code. Expected 200, got ' . $messageResponse->getStatusCode() . ' instead.'
                    );
                }

                $messageRequestId = $messageResponse->getHeader('Message-Request-Id')[0];
                $messageResponseId = $messageResponse->getHeader('Message-Response-Id')[0];

                if ($sentRequestId !== $messageRequestId) {
                    Log::debug('Message-Request-Id does not match. Deleting the message ' . $messageResponseId, [
                        'sentRequestId' => $sentRequestId,
                        'messageRequestId' => $messageRequestId
                    ]);

                    $this->deleteMessage($messageResponseId);

                    continue;
                }

                $contents = $messageResponse->getBody()->getContents();
                $response = simplexml_load_string($contents);

                if (false === $response) {
                    throw new RuntimeException('Failed to parse the response XML.');
                }

                if ($response->Error) {
                    throw new LhvApiError(
                        $response->Error->Description,
                        $response->Error->Code
                    );
                }

                return $response;
            }
        });
    }
}
