<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


use yii\helpers\Json;

class Image extends AbstractApi
{
    /**
     * @param bool $all
     * @param array $filters
     * @param bool $digests
     * @return array|mixed
     */
    public function lists($all = false, $filters = [], $digests = false)
    {
        return $this->get('images/json', [
            'all' => $all,
            'filters' => count($filters) == 0 ? '' : Json::encode($filters),
            'digests' => $digests
        ]);
    }

    /**
     * @param string $name
     * @return array|mixed
     */
    public function inspect($name)
    {
        return $this->get("images/{$name}/json");
    }
}
