<?php

namespace Swiftmade\LhvConnect\Requests;

class HeartbeatGetRequest extends BasicRequest
{
    protected $method = 'GET';
    protected $url = 'heartbeat';
}
