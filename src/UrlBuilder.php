<?php
/**
 * @link http://ipaya.cn/
 * @copyright Copyright (c) 2016 ipaya.cn
 */

namespace iPaya\Docker;


use yii\helpers\ArrayHelper;

class UrlBuilder
{
    public static function buildUri($uri, $params = [])
    {
        $sections = [];
        $info = parse_url($uri);
        if (isset($info['scheme'])) {
            $sections[] = $info['scheme'] . '://';
        }
        if (isset($info['host'])) {
            $sections[] = $info['host'];
        }
        if (isset($info['path'])) {
            $sections[] = str_replace('//', '/', '/' . $info['path']);
        }
        if (isset($info['query'])) {
            $query = $info['query'];
            $oldParams = [];
            foreach (explode('&', $query) as $pair) {
                list($name, $value) = explode('=', $pair);
                $oldParams[$name] = $value;
            }
            $params = ArrayHelper::merge($oldParams, $params);
        }

        $pairs = [];
        foreach ($params as $name => $value) {
            $pairs[] = "$name=$value";
        }
        $sections[] = '?' . implode('&', $pairs);

        return implode('', $sections);
    }
}
