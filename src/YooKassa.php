<?php

namespace Digkill\YooKassaLaravel;


use YooKassa\Client;

class YooKassa
{
    public const YOOKASSA_NAME_CONFIG = 'yookassa';

    /**
     * Configuration YooKassa
     *
     * @var array
     */
    private array $config;

    /**
     * YooKassa Client
     *
     * @var Client
     */
    private Client $client;

    public function __construct(array $config = [])
    {
        // Configuration
        $this->config = [...$config, ...config(self::YOOKASSA_NAME_CONFIG)];

        // Create Authorization
        $this->client->setAuth($this->config['shop_id'], $this->config['token']);
    }

}
