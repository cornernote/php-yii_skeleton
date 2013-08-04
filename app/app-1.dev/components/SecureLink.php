<?php
/**
 *
 */
class SecureLink
{

    /**
     * @static
     * @param $action
     * @param $params
     * @param $ttl
     * @return string
     */
    static public function getUrl($action, $params, $ttl)
    {
        $params['ttl'] = $ttl;
        $params['rnd'] = md5(microtime(true));
        $params['key'] = md5(implode('', $params) . Setting::item('hashKey'));
        return 'http://' . param('host') . url($action, $params);
    }

    /**
     * @static
     * @param $data
     * @param $paramNames
     * @return bool
     */
    static public function validate($data, $paramNames)
    {
        if (!isset($data['ttl']) || !isset($data['rnd'])) {
            return false;
        }
        if ($data['ttl'] < time()) {
            return false;
        }
        $params = array();
        foreach ($paramNames as $name) {
            if (isset($data[$name])) {
                $params[$name] = $data[$name];
            }
        }
        $params['ttl'] = $data['ttl'];
        $params['rnd'] = $data['rnd'];
        $key = md5(implode('', $params) . Setting::item('hashKey'));
        return ($data['key'] == $key);
    }

}
