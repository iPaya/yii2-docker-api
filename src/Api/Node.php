<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


use yii\helpers\Json;

class Node extends AbstractApi
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function lists($filters = [])
    {
        return $this->httpGet('nodes', [
            'filters' => count($filters) == 0 ? '' : Json::encode($filters),
        ]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function inspect($id)
    {
        return $this->httpGet("nodes/{$id}");
    }

    /**
     * @param string $id
     * @param bool $force
     * @return array
     */
    public function delete($id, $force = false)
    {
        return $this->httpDelete("nodes/{$id}?force={$force}");
    }

    /**
     * @param string $id
     * @param integer $version
     * @param array $params
     * @return array
     */
    public function update($id, $version, $params = [])
    {
        return $this->httpPost("nodes/{$id}/update?version={$version}", $params);
    }
}
