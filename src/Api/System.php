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
        return $this->get('info');
    }

    /**
     * @return array
     */
    public function version()
    {
        return $this->get('version');
    }

    /**
     * @return string
     */
    public function ping()
    {
        return $this->get('_ping');
    }

    /**
     * @return array
     */
    public function df()
    {
        return $this->get('system/df');
    }
}
