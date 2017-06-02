<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker\Api;


class System extends AbstractApi
{
    /**
     * @return array
     */
    public function info()
    {
        return $this->httpGet('info');
    }

    /**
     * @return array
     */
    public function version()
    {
        return $this->httpGet('version');
    }

    /**
     * @return string
     */
    public function ping()
    {
        return $this->httpGet('_ping');
    }

    /**
     * @return array
     */
    public function df()
    {
        return $this->httpGet('system/df');
    }
}
