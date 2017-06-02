<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker;


use iPaya\Docker\Api\AbstractApi;
use iPaya\Docker\Api\Container;
use iPaya\Docker\Api\Image;
use iPaya\Docker\Api\Node;
use iPaya\Docker\Api\Swarm;
use iPaya\Docker\Api\System;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\httpclient\Client as HttpClient;
use yii\httpclient\Response;

class Client extends Component
{
    public $host;
    public $port;
    public $version = 'v1.26';

    /**
     * @var HttpClient
     */
    private $_httpClient;


    public function init()
    {
        parent::init();
        if ($this->host == null) {
            throw new InvalidConfigException('请配置 "host"');
        }
        if ($this->port == null) {
            throw new InvalidConfigException('请配置 "port"');
        }
    }

    /**
     * @param string $name
     * @return AbstractApi|Container|Image|System|Swarm|Node
     * @throws Exception
     */
    public function api($name)
    {
        switch ($name) {
            case 'container':
                $api = new Api\Container($this);
                break;
            case 'image':
                $api = new Api\Image($this);
                break;
            case 'system':
                $api = new Api\System($this);
                break;
            case 'swarm':
                $api = new Api\Swarm($this);
                break;
            case 'node':
                $api = new Api\Node($this);
                break;
            default:
                throw new Exception("错误的 API '{$name}'");
        }
        return $api;
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function get($url, $data = [], $headers = [])
    {
        return $this->request('GET', $url, $data, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function post($url, $data = [], $headers = [])
    {
        return $this->request('POST', $url, $data, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function delete($url, $data = [], $headers = [])
    {
        return $this->request('DELETE', $url, $data, $headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed
     * @throws Exception
     */
    public function request($method, $url, $data = [], $headers = [])
    {
        $httpClient = $this->getHttpClient();
        $request = $httpClient->createRequest()
            ->setUrl($url)
            ->setHeaders($headers)
            ->setMethod($method)
            ->setData($data);
        if (strtolower($method) == 'post') {
            $request->setFormat('json');
        }
        return $this->parseResponse($request->send());
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if ($this->_httpClient == null) {
            $this->_httpClient = new HttpClient([
                'baseUrl' => 'http://' . $this->host . ':' . $this->port . '/' . $this->version,
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * @param Response $response
     * @return mixed
     * @throws Exception
     */
    public function parseResponse($response)
    {
        if ($response->isOk) {
            try {
                return Json::decode($response->content);
            } catch (\Exception $e) {
                return $response->content;
            }
        } else {
            try {
                $error = Json::decode($response->content);
            } catch (\Exception $exception) {
                $error = ['message' => $response->content];
            }
            throw new Exception($error['message']);
        }
    }
}
