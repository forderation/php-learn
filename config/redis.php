<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Predis\Client;

class Redis
{
    private $redis;

    public function __construct()
    {
        try {
            $this->redis = new Client(
                [
                    'scheme' => 'tcp',
                    'host' => '',
                    'port' => '12434'
                ],
                [
                    'parameters' => [
                        'password' => '',
                    ],
                ]
            );
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function SetCache($key, $value)
    {
        $ttl = 60;
        $this->redis->setex($key, $ttl, $value);
    }

    public function GetCache($key)
    {
        $value = $this->redis->get($key);
        return $value;
    }
}
