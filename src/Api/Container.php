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
        return $this->get('containers/json', $params);
    }
}
