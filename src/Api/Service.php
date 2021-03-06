<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


class Service extends AbstractApi
{
    /**
     * @param array $filters
     * @return array
     */
    public function lists($filters = [])
    {
        return $this->httpGet('services', ['filters' => $filters]);
    }

    /**
     * @param string $id
     * @return array
     */
    public function inspect($id)
    {
        return $this->httpGet("services/{$id}");
    }
}
