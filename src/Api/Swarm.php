<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


class Swarm extends AbstractApi
{
    /**
     * @return array
     */
    public function inspect()
    {
        return $this->get('swarm');
    }

    /**
     * @param array $params
     * @return array
     */
    public function init($params)
    {
        return $this->post('swarm/init', $params);
    }

    /**
     * @param $params
     * @return array
     */
    public function join($params)
    {
        return $this->post('swarm/join', $params);
    }
}
