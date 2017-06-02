<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


use iPaya\Docker\Client;

class AbstractApi
{
    /**
     * @var Client
     */
    protected $client;


    /**
     * @inheritDoc
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function get($url, $params = [], $headers = [])
    {
        return $this->client->get($url, $params, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function post($url, $data = [], $headers = [])
    {
        return $this->client->post($url, $data, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return array
     */
    public function delete($url, $data = [], $headers = [])
    {
        return $this->client->delete($url, $data, $headers);
    }
}
