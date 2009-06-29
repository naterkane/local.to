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
 * Sanitize
 * 
 * This class handles all data sanitization
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Sanitize {

	/**
	 * Sanitizes given array or value for safe input. Use the options to specify
	 * the connection to use, and what filters should be applied (with a boolean
	 * value). Valid filters: odd_spaces, encode, dollar, carriage, unicode,
	 * escape, backslash.
	 *
	 * @param mixed $data Data to sanitize
	 * @return mixed Sanitized data
	 * @access public
	 * @static
	 */
	public static function clean($data) {
		if (empty($data)) {
			return $data;
		}

		if (is_array($data)) {
			foreach ($data as $key => $val) {
				$data[$key] = Sanitize::clean($val);
			}
			return $data;
		} else {
			$data = trim($data);
			$data = Sanitize::html($data);
			return $data;
		}
	}

	/**
	 * Makes a string SQL-safe.
	 *
	 * @param string $string String to sanitize
	 * @return string SQL safe string
	 * @access public
	 * @static
	 */
	public static function escape($string) {
		return addslashes($string);
	}

	/**
	 * Returns given string safe for display as HTML. Renders entities.
	 *
	 * @param string $string String from where to strip tags
	 * @param boolean $remove If true, the string is stripped of all HTML tags
	 * @return string Sanitized string
	 * @access public
	 * @static
	 */
	public static function html($string, $remove = false) {
		if ($remove) {
			$string = strip_tags($string);
		} else {
			$string = htmlentities($string, ENT_COMPAT, 'UTF-8');
		}
		return $string;
	}

	/**
	 * Removes any non-alphanumeric characters.
	 *
	 * @param string $string String to sanitize
	 * @return string Sanitized string
	 * @access public
	 * @static
	 */
	public static function paranoid($string, $allowed = array()) {
		$allow = null;
		if (!empty($allowed)) {
			foreach ($allowed as $value) {
				$allow .= "\\$value";
			}
		}

		if (is_array($string)) {
			$cleaned = array();
			foreach ($string as $key => $clean) {
				$cleaned[$key] = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $clean);
			}
		} else {
			$cleaned = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $string);
		}
		return $cleaned;
	}

	/**
	 * Strips extra whitespace, images, scripts and stylesheets from output
	 *
	 * @param string $str String to sanitize
	 * @return string sanitized string
	 * @access public
	 */
	public static function stripAll($str) {
		$str = Sanitize::stripWhitespace($str);
		$str = Sanitize::stripImages($str);
		$str = Sanitize::stripScripts($str);
		return $str;
	}
	
	/**
	 * Strips image tags from output
	 *
	 * @param string $str String to sanitize
	 * @return string Sting with images stripped.
	 * @access public
	 * @static
	 */
	public static function stripImages($str) {
		$str = preg_replace('/(<a[^>]*>)(<img[^>]+alt=")([^"]*)("[^>]*>)(<\/a>)/i', '$1$3$5<br />', $str);
		$str = preg_replace('/(<img[^>]+alt=")([^"]*)("[^>]*>)/i', '$2<br />', $str);
		$str = preg_replace('/<img[^>]*>/i', '', $str);
		return $str;
	}
	
	/**
	 * Strips scripts and stylesheets from output
	 *
	 * @param string $str String to sanitize
	 * @return string String with <script>, <style>, <link> elements removed.
	 * @access public
	 * @static
	 */
	public static function stripScripts($str) {
		return preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/i', '', $str);
	}

	/**
	 * Strips the specified tags from output. First parameter is string from
	 * where to remove tags. All subsequent parameters are tags.
	 *
	 * @param string $str String to sanitize
	 * @param string $tag Tag to remove (add more parameters as needed)
	 * @return string sanitized String
	 * @access public
	 * @static
	 */
	public static function stripTags() {
		$params = params(func_get_args());
		$str = $params[0];

		for ($i = 1; $i < count($params); $i++) {
			$str = preg_replace('/<' . $params[$i] . '\b[^>]*>/i', '', $str);
			$str = preg_replace('/<\/' . $params[$i] . '[^>]*>/i', '', $str);
		}
		return $str;
	}

	/**
	 * Strips extra whitespace from output
	 *
	 * @param string $str String to sanitize
	 * @return string whitespace sanitized string
	 * @access public
	 * @static
	 */
	public static function stripWhitespace($str) {
		$r = preg_replace('/[\n\r\t]+/', '', $str);
		return preg_replace('/\s{2,}/', ' ', $r);
	}

}
?>