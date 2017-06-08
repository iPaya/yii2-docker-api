<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker;


use Http\Client\Common\Plugin\DecoderPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Socket\Client as SocketClient;
use Http\Message\MessageFactory;
use iPaya\Docker\Api\AbstractApi;
use iPaya\Docker\Api\Container;
use iPaya\Docker\Api\Image;
use iPaya\Docker\Api\Node;
use iPaya\Docker\Api\Swarm;
use iPaya\Docker\Api\System;
use Psr\Http\Message\ResponseInterface;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Client extends Component
{
    public $host;
    public $port;
    public $version = 'v1.26';
    public $unixSocket;
    public $connectionTimeout = 3;
    public $dataTimeout = 3;
    /**
     * @var MessageFactory
     */
    public $messageFactory;

    /**
     * @var SocketClient
     */
    private $_httpClient;


    public function init()
    {
        parent::init();
        $this->messageFactory = new MessageFactory\GuzzleMessageFactory();
        if ($this->unixSocket == null && $this->host == null) {
            throw new InvalidConfigException('"unixSocket" 或 "host" 必须设置其中一个.');
        }
        if ($this->unixSocket == null && $this->host != null && $this->port == null) {
            throw new InvalidConfigException('使用 "host" 连接 Docker 必须设置端口 "port".');
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
        $httpClient = $this->getSocketClient();
        if (strtolower($method) == 'get') {
            $url = UrlBuilder::buildUri($url, $data);
            $data = null;
        }
        if (strtolower($method) == 'post') {
            $data = Json::encode($data);
        }
        $headers = ArrayHelper::merge([
            'Host' => $this->version,
        ], $headers);
        $request = $this->messageFactory->createRequest($method, $url, $headers, $data);
        $response = $httpClient->sendRequest($request);
        return $this->parseResponse($response);
    }

    /**
     * @return SocketClient
     */
    public function getSocketClient()
    {
        if ($this->_httpClient == null) {
            $remoteSocket = $this->unixSocket ? ('unix://' . $this->unixSocket) : ('tcp://' . $this->host . ':' . $this->port);
            $client = new SocketClient($this->messageFactory, [
                'remote_socket' => $remoteSocket,

            ]);
            $plugins[] = new DecoderPlugin();
            $this->_httpClient = new PluginClient($client, $plugins);

        }
        return $this->_httpClient;
    }

    /**
     * @param ResponseInterface $response
     * @return mixed
     * @throws Exception
     */
    public function parseResponse($response)
    {
        $body = $response->getBody();
        $content = $body->getContents();
        if ($response->getStatusCode() == '200') {
            try {
                return Json::decode($content);
            } catch (\Exception $e) {
                return $content;
            }
        } else {
            try {
                $error = Json::decode($content);
            } catch (\Exception $exception) {
                $error = ['message' => $content];
            }
            throw new Exception($error['message']);
        }
    }
}
