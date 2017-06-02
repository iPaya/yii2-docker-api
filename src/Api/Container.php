<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


use yii\helpers\Json;

class Container extends AbstractApi
{
    /**
     * @param bool $all
     * @param int $limit
     * @param bool $size
     * @param array $filters
     * @return array
     */
    public function lists($all = false, $limit = 10, $size = false, $filters = [])
    {
        $params = [
            'all' => $all,
            'limit' => $limit,
            'size' => $size,
            'filters' => count($filters) == 0 ? '' : Json::encode($filters)
        ];
        return $this->httpGet('containers/json', $params);
    }

    /**
     * @param string $name
     * @param array $params
     * @return array
     */
    public function create($name, $params = [])
    {
        return $this->httpPost('containers/create?name=' . $name, $params);
    }

    /**
     * @param string $id
     * @param bool $size
     * @return array
     */
    public function inspect($id, $size = false)
    {
        $params = [
            'size' => $size
        ];
        return $this->httpGet("containers/{$id}/json", $params);
    }

    /**
     * @param string $id
     * @return string
     */
    public function logs($id)
    {
        return $this->httpGet("containers/{$id}/logs", [
            'stdout' => true,
            'stderr' => true,
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function stats($id)
    {
        return $this->httpGet("containers/{$id}/stats", [
            'stream' => false,
        ]);
    }

    /**
     * @param string $id
     * @return boolean|array
     */
    public function start($id)
    {
        $rs = $this->httpPost("containers/{$id}/start");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param string $id
     * @return bool|array
     */
    public function stop($id)
    {
        $rs = $this->httpPost("containers/{$id}/stop");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param string $id
     * @return bool|array
     */
    public function restart($id)
    {
        $rs = $this->httpPost("containers/{$id}/restart");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param string $id
     * @param string $newName
     * @return bool|array
     */
    public function rename($id, $newName)
    {
        $rs = $this->httpPost("containers/{$id}/rename?name={$newName}");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param string $id
     * @return bool|array
     */
    public function pause($id)
    {
        $rs = $this->httpPost("containers/{$id}/pause");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param string $id
     * @return bool|array
     */
    public function unause($id)
    {
        $rs = $this->httpPost("containers/{$id}/unpause");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param string $id
     * @param bool $force
     * @param bool $v
     * @return array|bool
     */
    public function remove($id, $force = false, $v = false)
    {
        $rs = $this->httpDelete("containers/{$id}?force={$force}&v=$v");
        if ($rs == '') {
            return true;
        }
        return $rs;
    }

    /**
     * @param array $filters
     * @return array
     */
    public function prune($filters = [])
    {
        if (count($filters) == 0) {
            $filters = '';
        } else {
            $filters = Json::encode($filters);
        }
        return $this->httpPost('containers/prune?filters=' . $filters);
    }
}
