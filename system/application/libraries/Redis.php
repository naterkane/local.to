<?php
/*******************************************************************************
 * Redis PHP Bindings - http://code.google.com/p/redis/
 *
 * Copyright 2009 Ludovico Magnocavallo
 * Released under the same license as Redis.
 *
 * Version: 0.1
 *
 * $Revision$
 * $Date$
 *
 ******************************************************************************/

class Redis {
   
    var $server;
    var $port;
    var $_sock;
 
 	/**
 	 * 
 	 * @constructor
 	 * @return 
 	 * @param object $config[optional]
 	 */
	function __construct($config = array()) {
		if (empty($config['port'])) {
			// for redis
			$config['port'] = 6379;
			// for memcachedb
			//$config['port'] = 21201;
		}
		if (empty($config['host'])) {
			$config['host'] = 'localhost';
		}
		$this->host = $config['host'];
		$this->port = $config['port'];
	}
	
	/**
	 * Connect to the Redis database
	 * 
	 * @return 
	 */
    function connect() {
        if ($this->_sock)
            return;
        if ($sock = fsockopen($this->host, $this->port, $errno, $errstr)) {
            $this->_sock = $sock;
            return;
        }
        $msg = "Cannot open socket to {$this->host}:{$this->port}";
        if ($errno || $errmsg)
            $msg .= "," . ($errno ? " error $errno" : "") . ($errmsg ? " $errmsg" : "");
        trigger_error("$msg.", E_USER_ERROR);
    }

	/**
	 * Disconnect from the Redis database
	 * 
	 * @return 
	 */
    function disconnect() {
        if ($this->_sock)
            @fclose($this->_sock);
        $this->_sock = null;
    }
	
	/**
	 * Ping the database
	 * 
	 * @return 
	 */
    function ping() {
        $this->connect();
        $this->_write("PING\r\n");
        return $this->_simple_response();
    }

	/**
	 * 
	 * @return 
	 * @param string $s
	 */
    function do_echo($s) {
        $this->connect();
        $this->_write("ECHO " . strlen($s) . "\r\n$s\r\n");
        return $this->_get_value();
    }
	
	/**
	 * Set a key to a string value
	 * 
	 * @return 
	 * @param string $name
	 * @param string $value
	 * @param boolean $preserve[optional]
	 */
    function set($name, $value, $preserve=false) {
        $this->connect();
        $this->_write(
            ($preserve ? 'SETNX' : 'SET') .
            " $name " . strlen($value) . "\r\n$value\r\n"
        );
        return $preserve ? $this->_numeric_response() : $this->_simple_response();
    }
	
	/**
	 * Return the string value of the key
	 * 
	 * @return 
	 * @param object $name
	 */
    function get($name) {
        $this->connect();
        $this->_write("GET $name\r\n");
        return $this->_get_value();
    }

	/**
	 * Increment the integer value of key
	 * 
	 * @return 
	 * @param string $name
	 * @param number $amount[optional] increment amount, default is 1
	 */
    function incr($name, $amount=1) {
        $this->connect();
        if ($amount == 1)
            $this->_write("INCR $name\r\n");
        else
            $this->_write("INCRBY $name $amount\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Decrement the integer value of key
	 * 
	 * @return 
	 * @param string $name
	 * @param number $amount[optional] decrement amount, default is 1
	 */
    function decr($name, $amount=1) {
        $this->connect();
        if ($amount == 1)
            $this->_write("DECR $name\r\n");
        else
            $this->_write("DECRBY $name $amount\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Test if a key exists
	 * 
	 * @return 
	 * @param string $name
	 */
    function exists($name) {
        $this->connect();
        $this->_write("EXISTS $name\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Delete a key
	 * 
	 * @return 
	 * @param string $name
	 */
    function delete($name) {
        $this->connect();
        $this->_write("DEL $name\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Return all the keys matching a given pattern
	 * 
	 * @return 
	 * @param string $pattern
	 * @todo do some testing to see what "patterns" $pattern will accept, assuming string match & not regex
	 */
    function keys($pattern) {
        $this->connect();
        $this->_write("KEYS $pattern\r\n");
        return explode(' ', $this->_get_value());
    }

	/**
	 * Returns a random key from the key space
	 * 
	 * @return string $s
	 */
    function randomkey() {
        $this->connect();
        $this->_write("RANDOMKEY\r\n");
        $s =& trim($this->_read());
        $this->_check_for_error($s);
        return $s;
    }

	/**
	 * Rename the old key in the new one, destroing the newname key if it already exists
	 * 
	 * @return 
	 * @param string $src
	 * @param string $dst
	 * @param boolean $preserve[optional]
	 */
    function rename($src, $dst, $preserve=false) {
        $this->connect();
        if ($preserve) {
            $this->_write("RENAMENX $src $dst\r\n");
            return $this->_numeric_response();
        }
        $this->_write("RENAME $src $dst\r\n");
        return trim($this->_simple_response());
    }

	/**
	 * Append an element to the tail or head of the List value at key.
	 * 
	 * @return 
	 * @param string $name
	 * @param string $value
	 * @param boolean $tail[optional]
	 */
    function push($name, $value, $tail=true) {
        // default is to append the element to the list
        $this->connect();
        $this->_write(
            ($tail ? 'RPUSH' : 'LPUSH') .
            " $name " . strlen($value) . "\r\n$value\r\n"
        );
        return $this->_simple_response();
    }

	/**
	 * Trim the list at key to the specified range of elements
	 * 
	 * @return 
	 * @param string $name
	 * @param number $start
	 * @param number $end
	 */
    function ltrim($name, $start, $end) {
        $this->connect();
        $this->_write("LTRIM $name $start $end\r\n");
        return $this->_simple_response();
    }
	
	/**
	 * Return the element at the index position from the List at key
	 * 
	 * @return 
	 * @param string $name
	 * @param number $index
	 */
    function lindex($name, $index) {
        $this->connect();
        $this->_write("LINDEX $name $index\r\n");
        return $this->_get_value();
    }

	/**
	 * Return and remove (automatically) the first or last element of the List at key.
	 * @return 
	 * @param string $name
	 * @param boolean $tail[optional]
	 */
    function pop($name, $tail=true) {
        $this->connect();
        $this->_write(
            ($tail ? 'RPOP' : 'LPOP') .
            " $name\r\n"
        );
        return $this->_get_value();
    }

	/**
	 * Return the length of the List value at key
	 * 
	 * @return 
	 * @param string $name
	 */
    function llen($name) {
        $this->connect();
        $this->_write("LLEN $name\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Return a range of elements from the List at key
	 * 
	 * @return 
	 * @param string $name
	 * @param number $start
	 * @param number $end
	 */
    function lrange($name, $start, $end) {
        $this->connect();
        $this->_write("LRANGE $name $start $end\r\n");
        return $this->_get_multi();
    }

	/**
	 * Sort a Set or a List accordingly to the specified parameters
	 * key BY pattern LIMIT start end GET pattern ASC|DESC ALPHA
	 * 
	 * @return 
	 * @param string $name
	 * @param string $query[optional]
	 */
    function sort($name, $query=false) {
        $this->connect();
        if ($query === false) {
            $this->_write("SORT $name\r\n");
        } else {
            $this->_write("SORT $name $query\r\n");
        }
        return $this->_get_multi();
    }

	/**
	 * Set a new value as the element at index position of the List at key
	 * 
	 * @return 
	 * @param string $name
	 * @param string $value
	 * @param number $index
	 */
    function lset($name, $value, $index) {
        $this->connect();
        $this->_write("LSET $name $index " . strlen($value) . "\r\n$value\r\n");
        return $this->_simple_response();
    }

	/**
	 * Add the specified member to the Set value at key
	 * 
	 * @return 
	 * @param string $name
	 * @param string $value
	 */
    function sadd($name, $value) {
        $this->connect();
        $this->_write("SADD $name " . strlen($value) . "\r\n$value\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Remove the specified member from the Set value at key
	 * 
	 * @return 
	 * @param string $name
	 * @param string $value
	 */
    function srem($name, $value) {
        $this->connect();
        $this->_write("SREM $name " . strlen($value) . "\r\n$value\r\n");
        return $this->_numeric_response();
    }
	
	/**
	 * Test if the specified value is a member of the Set at key
	 * 
	 * @return 
	 * @param string $name
	 * @param strimg $value
	 */
    function sismember($name, $value) {
        $this->connect();
        $this->_write("SISMEMBER $name " . strlen($value) . "\r\n$value\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Return the intersection between the Sets stored at key1, key2, ..., keyN
	 * 
	 * @return 
	 * @param string $sets space separated string of Sets to query
	 */
    function sinter($sets) {
        $this->connect();
        $this->_write('SINTER ' . implode(' ', $sets) . "\r\n");
        return $this->_get_multi();
    }
	
	/**
	 * Return all the members of the Set value at key
	 * 
	 * @return 
	 * @param string $name
	 */
    function smembers($name) {
        $this->connect();
        $this->_write("SMEMBERS $name\r\n");
        return $this->_get_multi();
    }

	/**
	 * Return the number of elements (the cardinality) of the Set at key
	 * 
	 * @return 
	 * @param string $name
	 */
    function scard($name) {
        $this->connect();
        $this->_write("SCARD $name\r\n");
        return $this->_numeric_response();
    }
	
	/**
	 * Select the DB having the specified index
	 * 
	 * @return 
	 * @param string $name
	 */
    function select_db($name) {
        $this->connect();
        $this->_write("SELECT $name\r\n");
        return $this->_simple_response();
    }

	/**
	 * Move the key from the currently selected DB to the DB having as index $db
	 * 
	 * @return 
	 * @param string $name
	 * @param string $db
	 */
    function move($name, $db) {
        $this->connect();
        $this->_write("MOVE $name $db\r\n");
        return $this->_numeric_response();
    }
	
	/**
	 * Synchronously or Asynchronously save the DB on disk
	 * 
	 * @return 
	 * @param boolean $background[optional]
	 */
    function save($background=false) {
        $this->connect();
        $this->_write(($background ? "BGSAVE\r\n" : "SAVE\r\n"));
        return $this->_simple_response();
    }

	/**
	 * Return the UNIX time stamp of the last successfully saving of the dataset on disk
	 * 
	 * @return string unix time stamp
	 */
    function lastsave() {
        $this->connect();
        $this->_write("LASTSAVE\r\n");
        return $this->_numeric_response();
    }

	/**
	 * Remove all the keys from all the databases
	 * 
	 * @return 
	 */
	function flush() {
        $this->connect();
        $this->_write("FLUSHALL\r\n");
	}

	/**
	 * Write to db
	 * 
	 * @private
	 * @return 
	 * @param object $s
	 */
    private function _write($s) {
        while ($s) {
            $i = fwrite($this->_sock, $s);
            if ($i == 0)
                break;
            $s = substr($s, $i);
        }
    }

	/**
	 * Read from socket
	 * 
	 * @private
	 * @return 
	 * @param boolean $len[optional]
	 */
    private function _read($len=1024) {
        if ($s = fgets($this->_sock))
            return $s;
        $this->disconnect();
        trigger_error("Cannot read from socket.", E_USER_ERROR);
    }
	
	/**
	 * Check for error
	 * 
	 * @private
	 * @return 
	 * @param string $s
	 */
    private function _check_for_error(&$s) {
        if (!$s || $s[0] != '-')
            return;
        if (substr($s, 0, 4) == '-ERR')
            trigger_error("Redis error: " . trim(substr($s, 4)), E_USER_ERROR);
        trigger_error("Redis error: " . substr(trim($this->_read()), 5), E_USER_ERROR);
    }
	
	/**
	 * 
	 * @private
	 * @return 
	 */
    private function _simple_response() {
        $s =& trim($this->_read());
        if ($s[0] == '+')
            return substr($s, 1);
        if ($err =& $this->_check_for_error($s))
            return $err;
        trigger_error("Cannot parse first line '$s' for a simple response", E_USER_ERROR);
    }

	/**
	 * Convert to numeric and return
	 * 
	 * @private
	 * @return 
	 * @param boolean $allow_negative[optional]
	 */
    private function _numeric_response($allow_negative=True) {
        $s =& trim($this->_read());
        $i = (int)$s;
        if ($i . '' == $s) {
            if (!$allow_negative && $i < 0)
                $this->_check_for_error($s);
            return $i;
        }
        if ($s == 'nil')
            return null;
        trigger_error("Cannot parse '$s' as numeric response.");
    }
	
	/**
	 * 
	 * @private
	 * @return 
	 */
    private function _get_value() {
        $s =& trim($this->_read());
        if ($s == 'nil')
            return '';
        else if ($s[0] == '-')
            $this->_check_for_error($s);
        $i = (int)$s;
        if ($i . '' != $s)
            trigger_error("Cannot parse '$s' as data length.");
        $buffer = '';
        while ($i > 0) {
            $s = $this->_read();
            $l = strlen($s);
            $i -= $l;
            if ($l > $i) // ending crlf
                $s = rtrim($s);
            $buffer .= $s;
        }
        if ($i == 0)    // let's restore the trailing crlf
            $buffer .= $this->_read();
        return $buffer;
    }
	
	/**
	 * 
	 * @private
	 * @return 
	 */
    private function _get_multi() {
        $results = array();
        $num =& $this->_numeric_response(false);
        if ($num === false)
            return $results;
        while ($num) {
            $results[] =& $this->_get_value();
            $num -= 1;
        }
        return $results;
    }
   
}  


?>
