<?php
require_once(dirname(dirname(__FILE__)) . '/Tt.php');
require_once(dirname(dirname(__FILE__)) . '/TokyoTyrant/Query.php');

class Net_TokyoTyrant_Table extends Net_TokyoTyrant
{
    /* public const */
    const ITLEXICAL = 0;
    const ITDECIMAL = 1;
    const ITTOKEN   = 2;
    const ITQGRAM   = 3;
    const ITOPT     = 9999;
    const ITVOID    = 9999;
    const ITKEEP    = 0x1000000; //1 << 24 umm..  

    public function put($key, $values)
    {
        $params = array();
        $params[] = $key;
        foreach ($values as $name => $value) {
            $params[] = $name;
            $params[] = $value;
        }

        try {
            $this->misc('put', $params, 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }
        return true;
    }

    public function putkeep($key, $values)
    {
        $params = array();
        $params[] = $key;
        foreach ($values as $name => $value) {
            $params[] = $name;
            $params[] = $value;
        }
        try {
            $this->misc('putkeep', $params, 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }
        return true;
    }

    public function putcat($key, $values)
    {
        $params = array();

        foreach ($values as $key => $value) {
            $params[] = $key;
            $params[] = $value;
        }

        try {
            $this->misc('putcat', $params, 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }

        return true;
    }

    public function out($key)
    {
        $params = array();
        $params[] = $key;

        try {
            $this->misc('out', $params, 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }

        return true;
    }

    public function get($key)
    {
        $params = array();
        $params[] = $key;
        try {
            $values = $this->misc('get', $params, 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;            
        }
        $values_count = count($values);
        $result = array();
        for ($i = 0;$i < $values_count; $i+=2) {
            $result[$values[$i]] = $values[$i + 1];   
        }
        return $result;
    }


    public function mget($keys)
    {
        $values = parent::mget($keys);

        $result = array();
        foreach ($values as $value) {
            $col = explode("\0", $value);
            $col_count = count($col);
            $data = array();
            for ($i = 0; $i < $col_count ; $i+=2) {
                $data[$col[$i]] = $col[$i + 1];
            }
            $result[] = $data;
        }
        return $result;
    }


    public function setindex($name, $type)
    {
        $params = array();
        $params[] = $name;
        $params[] = $type;

        try {
            $this->misc('setindex', $params, 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }
        return true;
    }

    public function genuid()
    {
        try {
            $result = $this->misc('genuid', array(), 0);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }
        return $result[0];

    }

    public function getQuery()
    {
        return new Net_TokyoTyrant_Query($this);        
    }
}