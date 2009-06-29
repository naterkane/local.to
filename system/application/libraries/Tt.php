<?php
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
 /**
 * Net_TokyoTyrantException
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Exception
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Net_TokyoTyrantException extends Exception {};
 /**
 * Tt
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Tt
{
    private $connect = false;
    private $socket;
    private $errorNo, $errorMessage;

    const RDBXOLCKNON = 0;
    const RDBXOLCKREC = 1;
    const RDBXOLCKGLB = 2;
    
    public function connect($server, $port, $timeout = 10)
    {
        $this->socket = @fsockopen($server,$port, $this->errorNo, $errorMessage);
        if (! $this->socket) {
            throw new Net_TokyoTyrantException(sprintf('%s, %s', $this->errorNo, $errorMessage));
        }
    }

    public function close()
    {
        fclose($this->socket);
    }


    private function _read($length)
    {
        if (@feof($this->socket)) 
        {
            throw new Net_TokyoTyrantException('socket read eof error');
        }
		$result = null;
		if ($length) 
		{
	        $result = fread($this->socket, $length);
	        if ($result === false) {
	            throw new Net_TokyoTyrantException('socket read error');
	        }
		}
        return $result;
    }


    private function _write($data)
    {
        $result = fwrite($this->socket, $data);
        if ($result === false) {
            throw new Net_TokyoTyrantException('socket read error');
        }
    }

    private function _doRequest($cmd, $values = array())
    {
        $this->_write($cmd . $this->_makeBin($values));
    }

    private function _makeBin($values){
        $int = '';
        $str = '';

        foreach ($values as  $value) {
            if (is_array($value)) {
                $str .= $this->_makeBin($value);
                continue;
            }

            if (! is_int($value)) {
                $int .= pack('N', strlen($value));
                $str .= $value;
                continue;
            } 

            $int .= pack('N', $value);
        }
        return $int . $str;
    }


    protected function _getResponse()
    {
        $res = fread($this->socket, 1);
        $res = unpack('c', $res);
        if ($res[1] !== 0) {
            return false;
        }
        return true; 
    }
    

    protected function _getInt1()
    {
        $result = '';
        $res = $this->_read(1);
        $res = unpack('c', $res);
        return $res[1];
    }

    protected function _getInt4()
    {
        $result = '';
        $res = $this->_read(4);
        $res = unpack('N', $res);
        return $res[1];
    }

    protected function _getInt8()
    {
        $result = '';
        $res = $this->_read(8);
        $res = unpack('N*', $res);
        return array($res[1], $res[2]);
    }

    protected function _getValue()
    {
        $result = '';
        $size = $this->_getInt4();
        return $this->_read($size);
    }


    protected function _getKeyValue()
    {
        $result = array();
        $ksize = $this->_getInt4();
        $vsize = $this->_getInt4();
        $result[] = $this->_read($ksize);
        $result[] = $this->_read($vsize);
        return $result;
    }

    protected function _getData()
    {
        $result = '';
        $size = $this->_getInt4();
        if ($size === 0) {
            return '';
        }
        return $this->_read((int) $size);
    }
    
    protected function _getDataList()
    {
        $result = array();
        
        $listCount = $this->_getInt4();
        for($i = 0;$i < $listCount; $i++) {
            $result[] = $this->_getValue();
        }
        return $result;
    }


    protected function _getKeyValueList()
    {
        $result = array();
        
        $listCount = $this->_getInt4();
        for($i = 0;$i < $listCount; $i++) {
            list($key, $value)  = $this->_getKeyValue();
            $result[$key] = $value;
        }
        return $result;
    }

    public function put($key, $value)
    {
        $cmd = pack('c*', 0xC8,0x10);
        $this->_doRequest($cmd, array($key, $value));
        return $this->_getResponse();
    }
    
    public function putkeep($key, $value)
    {
        $cmd = pack('c*', 0xC8,0x11);
        $this->_doRequest($cmd, array($key, $value));
        return $this->_getResponse();
    }
    
    public function putcat($key, $value)
    {
        $cmd = pack('c*', 0xC8,0x12);
        $this->_doRequest($cmd, array($key, $value));
        return $this->_getResponse();
    }
    
    public function putrtt($key, $value, $width)
    {
        $cmd = pack('c*', 0xC8,0x13);
        $this->_doRequest($cmd, array($key, $value, $width));
        return $this->_getResponse();
    }

    public function putnr($key, $value)
    {
        $cmd = pack('c*', 0xC8,0x18);
        $this->_doRequest($cmd, array($key, $value, $width));
    }

    public function out($key)
    {
        $cmd = pack('c*', 0xC8,0x20);
        $this->_doRequest($cmd, array($key));
        return $this->_getResponse();
    }
    
    public function get($key)
    {
        $cmd = pack('c*', 0xC8,0x30);
        $this->_doRequest($cmd, array($key));
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getData();
    }
    
    public function mget($keys)
    {
        $cmd = pack('c*', 0xC8,0x31);
        $values = array();
        $values[] = count($keys);
        foreach($keys as $key) {
          $values[] = array($key);
        }
        
        $this->_doRequest($cmd, $values);
        if ($this->_getResponse() === false) {
            return false;
        }
        
        return $this->_getKeyValueList();
    }

    public function fwmkeys($prefix, $max)
    {
        $cmd = pack('c*', 0xC8,0x58);
        $this->_doRequest($cmd, array($prefix, $max));
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getDataList();
    }
    
    public function addint($key, $num)
    {
        $cmd = pack('c*', 0xC8,0x60);
        $this->_doRequest($cmd, array($key, $num));
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getInt4();
    }
  
    public function putint($key, $num)
    {
        //This Code is non support
        $value = pack('V', $num);
        return $this->put($key, $value);
    }
  
    public function getint($key)
    {
        return $this->addint($key, 0);
    }

    public function adddouble($key, $integ, $fract)
    {
        $cmd = pack('c*', 0xC8,0x61);
        $this->_doRequest($cmd, array($key, $intteg, $fract));
        if ($this->_getResponse() === false) {
            return false;
        }
        return array($this->_getInt8(), $this->_getInt8());
    }


    public function ext($extname, $key, $value, $option = 0)
    {
        $cmd = pack('c*', 0xC8,0x68);
        $this->_doRequest($cmd, array($extname, $option, $key, $value));
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getData();
    }

    public function vsize($key)
    {
        $cmd = pack('c*', 0xC8,0x38);
        $this->_doRequest($cmd, array($key));
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getInt4();
    }
    
    public function iterinit()
    {
        $cmd = pack('c*', 0xC8,0x50);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return true;
    }
    
    public function iternext()
    {
        $cmd = pack('c*', 0xC8,0x51);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getValue();
    }

    public function sync()
    {
        $cmd = pack('c*', 0xC8,0x70);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return true;
    }
    
    public function vanish()
    {
        $cmd = pack('c*', 0xC8,0x71);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return true;
    }
    
    public function copy($path)
    {
        $cmd = pack('c*', 0xC8,0x72);
        $this->_doRequest($cmd, array($path));
        if ($this->_getResponse() === false) {
            return false;
        }
        return true;
    }
    
    public function restore($path)
    {
        $cmd = pack('c*', 0xC8,0x73);
        $this->_doRequest($cmd, array($path));
        if ($this->_getResponse() === false) {
            return false;
        }
        return true;
    }
    
    public function setmst($host, $port)
    {
        $cmd = pack('c*', 0xC8,0x78);
        $this->_doRequest($cmd, array($host,$port));
        if ($this->_getResponse() === false) {
            return false;
        }
        return true;
    }
 
    public function rnum()
    {
        $cmd = pack('c*', 0xC8,0x80);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getInt8();
    }
 
    public function size()
    {
        $cmd = pack('c*', 0xC8,0x81);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getInt8();
    }

    public function stat()
    {
        $cmd = pack('c*', 0xC8,0x88);
        $this->_doRequest($cmd);
        if ($this->_getResponse() === false) {
            return false;
        }
        return $this->_getValue();
    }
}
?>