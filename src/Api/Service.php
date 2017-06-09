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

    public function create($data)
    {
        $data = [];
        return $this->httpPost('service/create', $data);
    }
}
