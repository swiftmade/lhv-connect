<?php

namespace Swiftmade\LhvConnect\Tests;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Swiftmade\LhvConnect\LhvConnect;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;

class LhvConnectTest extends TestCase
{
    public function test_a_correct_heartbeat_request_data()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
            new RequestException('Error Communicating with Server', new Request('GET', 'heartbeat')),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $connect = new LhvConnect([
            'url' => 'https://connect.prelive.lhv.eu',
            'cert' => [
                'path' => __DIR__ . '/test_cert.p12',
                'password' => 'changeit',
            ],
            'handler' => $handlerStack,
        ]);

        $connect->sendHeartbeat();

        $this->assertEquals(
            '/heartbeat',
            $mock->getLastRequest()->getUri()->getPath()
        );

        // Make sure that the certificate is set correctly.
        $this->assertEquals(
            [
                __DIR__ . '/test_cert.p12',
                'changeit',
            ],
            $mock->getLastOptions()['cert']
        );
    }

    public function test_failed_heartbeat_request()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(503, ['X-Foo' => 'Bar'], 'Service Unavailable'),
            new RequestException('Error Communicating with Server', new Request('GET', 'heartbeat')),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $connect = new LhvConnect([
            'url' => 'https://connect.prelive.lhv.eu',
            'cert' => [
                'path' => __DIR__ . '/test_cert.p12',
                'password' => 'changeit',
            ],
            'handler' => $handlerStack,
        ]);

        $this->expectException(ServerException::class);
        $connect->sendHeartbeat();
    }
}
