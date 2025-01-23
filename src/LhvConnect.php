<?php

namespace Swiftmade\LhvConnect;

use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Swiftmade\LhvConnect\Requests\AbstractRequest;
use Swiftmade\LhvConnect\Requests\AccountBalanceRequest;
use Swiftmade\LhvConnect\Requests\AccountStatementRequest;
use Vyuldashev\XmlToArray\XmlToArray;

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
        return Cache::lock('lhv-connect-request', 30)->block(2, function () use ($request) {

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
            $maxRetries = 5;

            while (true) {
                if (++$retries === $maxRetries) {
                    throw new RuntimeException('Max retries reached. No matching response found.');
                }

                // Exponential backoff
                usleep((2 ** $retries) * 100_000);

                // Log::debug('Checking for the next message. Retries: ' . $retries);

                $messageResponse = $this->client->get('/messages/next', [
                    'headers' => [
                        'Filter-Response-Type' => $request->responseType()
                    ],
                ]);

                if ($messageResponse->getStatusCode() === 204) {
                    // Log::debug('No message found. Retrying...');
                    continue;
                }

                if ($messageResponse->getStatusCode() !== 200) {
                    throw new RuntimeException(
                        'Unexpected status code. Expected 200, got ' . $messageResponse->getStatusCode() . ' instead.'
                    );
                }

                $messageRequestId = $messageResponse->getHeader('Message-Request-Id')[0];
                $messageResponseId = $messageResponse->getHeader('Message-Response-Id')[0];

                if ($sentRequestId !== $messageRequestId) {
                    // Log::debug('Message-Request-Id does not match. Deleting the message ' . $messageResponseId, [
                    //     'sentRequestId' => $sentRequestId,
                    //     'messageRequestId' => $messageRequestId
                    // ]);

                    $this->deleteMessage($messageResponseId);

                    continue;
                }

                $rawResponse = $messageResponse->getBody()->getContents();
                $response = XmlToArray::convert($rawResponse);

                if (isset($response['Errors'])) {
                    throw new LhvApiError(
                        $response['Errors']['Error']['Code'] . ' - ' . $response['Errors']['Error']['Description'],
                    );
                }

                return $response;
            }
        });
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

    public function getAccountBalance(string $accountIban = null)
    {
        if (is_null($accountIban)) {
            $accountIban = $this->configuration['IBAN'];
        }

        if (empty($accountIban)) {
            throw new \Exception('Account IBAN is required either in the method or in the configuration file.');
        }

        $request = new AccountBalanceRequest(
            $accountIban
        );

        return $this->runRequest($request);
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
}
