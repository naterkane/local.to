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
 * Page
 * 
 * This class handles and manages pagination
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Page
{
	
	/**
	 * Last number of records to fetch
	 * @access public
	 * @static
	 * @var int
	 */	
	public static $end = 1;
	/**
	 * Path for next link
	 * @access public
	 * @static
	 * @var string
	 */		
	public static $next;
	/**
	 * Text to display for next link
	 * @access public
	 * @static
	 * @var string
	 */
	public static $nextText = 'view older';	
	/**
	 * Offset for pagination count
	 * @access public
	 * @static
	 * @var int
	 */	
	public static $offset = 20;
	/**
	 * Page being viewed
	 * @access public
	 * @static
	 * @var int
	 */	
	public static $page = 1;
	/**
	 * Path for previous link
	 * @access public
	 * @static
	 * @var string
	 */	
	public static $previous;
	/**
	 * Text to display for link
	 * @access public
	 * @static
	 * @var string
	 */				
	public static $previousText = 'view newer';
	/**
	 * Show a 'next' link?
	 * @access public
	 * @static
	 * @var boolean
	 */	
	public static $showNext = false;
	/**
	 * Show a 'previous' link?
	 * @access public
	 * @static
	 * @var boolean
	 */	
	public static $showPrevious = false;	
	/**
	 * Start count
	 * @access public
	 * @static
	 * @var int
	 */	
	public static $start = 0;
	
	
	/**
	 * Set up paging
	 *
	 * @access public
	 * @static	
	 * @param array $segments
	 * @return 
	 */
	public static function setUp(&$segments = array())
	{
		$count = count($segments);
		self::$end = self::$offset;
		if (isset($segments[$count - 2]) && isset($segments[$count - 1])) 
		{
			if (($segments[$count - 2] == 'page') && (is_numeric($segments[$count - 1])))
			{
				if ($segments[$count - 1] > 1)
				{
					self::$page = $segments[$count - 1];
					self::$start = (self::$page - 1) * self::$offset;
					self::$end = self::$offset * self::$page;
				}		
				unset($segments[$count - 2]);
				unset($segments[$count - 1]);
			}		
		}		
		self::$next = '/' . implode('/', $segments) . '/page/';
		self::$next .= self::$page + 1;
		if (self::$page > 1) 
		{
			self::$previous = '/' . implode('/', $segments) . '/page/';
			self::$previous .= self::$page - 1;
		}
	}
	
	
	/**
	 * Paginate data to send to views
	 *
	 * @access public
	 * @static	
	 * @param string $model Name of the model to be queried, upppercase
	 * @param array $data Array of keys to be queried
	 * @param array $options
	 * [method] = Name of the method to be applied to the array of keys. Must have variables for $start and $end. Defaults to Message::getMany
	 * @return array $data
	 */
	public static function make($model, $data, $options = array())
	{		
		if (!isset($options['method'])) 
		{
			$method = 'getMany';
		}
		else
		{
			$method = $options['method'];
		}
		$ci =& get_instance();
		$count = count($data);
		if ($count > self::$end) 
		{
			self::$showNext = true;
		}
		if ((self::$end - self::$offset) > 0)
		{
			self::$showPrevious = true;
		}
		$return = $ci->$model->$method($data, self::$start, self::$end, $options);
		if (empty($return) AND self::$page > 1)
		{
			$ci->show404();
			unset($ci);			
		}
		else 
		{
			unset($ci);
			return $return;
		}
	}
	
	/**
	 * Display pagination links
	 *
	 * @access public
	 * @static
	 * @var object $html Html helper	
	 * @return string 
	 */
	public static function links(&$html)
	{
		$return = null;
		if (self::$showNext) 
		{
			$return .= "<span id=\"page-next\">&laquo; " . $html->link(self::$nextText, self::$next) . "</span> \n";
		}
		if (self::$showPrevious) 
		{
			$return .= "<span id=\"page-previous\">" . $html->link(self::$previousText, self::$previous) . " &raquo;</span>\n";
		}		
		return $return;
	}
	
}
?>