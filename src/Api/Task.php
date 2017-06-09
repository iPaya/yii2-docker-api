<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


class Task extends AbstractApi
{
    /**
     * @param array $filters
     * @return array
     */
    public function lists($filters = [])
    {
        return $this->httpGet('/tasks', ['filters' => $filters]);
    }

    /**
     * @param string $id
     * @return array
     */
    public function inspect($id)
    {
        return $this->httpGet("/tasks/{$id}");
    }
}
