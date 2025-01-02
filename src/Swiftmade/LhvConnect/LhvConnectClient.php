<?php

namespace Swiftmade\LhvConnect;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class LhvConnectClient extends Client
{
    private array $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;

        $options = [
            'base_uri' => $this->configuration['url'],
            RequestOptions::CERT => [
                $this->configuration['cert']['path'],
                $this->configuration['cert']['password'],
            ],
        ];

        if (isset($this->configuration['ssl_key'])) {
            $options[RequestOptions::SSL_KEY] = [
                $this->configuration['ssl_key']['path'],
                $this->configuration['ssl_key']['password'],
            ];
        }

        if (isset($this->configuration['verify'])) {
            $options[RequestOptions::VERIFY] = $this->configuration['verify'];
        }

        // For testing purposes...
        if (isset($this->configuration['handler'])) {
            $options['handler'] = $this->configuration['handler'];
        }

        parent::__construct($options);
    }
}
