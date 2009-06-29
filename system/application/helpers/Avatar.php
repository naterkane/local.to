<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
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
 * Avatar Helper
 * 
 * Display an avatar
 * 
 * @package 	Nomcat
 * @subpackage	Helpers
 * @category	Helpers
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Avatar extends Html
{
	/**
	 * The default path of where avatars are stored in the filesystem
	 * @var string
	 */
	public $defaultPath = '/img/avatar';
	
	/**
	 * The default size of which an avatar should be displayed
	 * @var string
	 * @todo allow to be overriden by theme config
	 */
	var $defaultSize = "48";
	
	/**
	 * Get and return html to display the avatar of a group
	 * 
	 * @access public
	 * @param object $user[optional]
	 * @param string $size[optional]
	 * @return string
	 */
	public function group($user = array(), $size = null)
	{
		return $this->make($user, $size, true);
	}

	/**
	 * Get and return html to display the avatar of a user
	 * 
	 * @access public
	 * @param object $user[optional]
	 * @param string $size[optional]
	 * @return string
	 */
	public function user($user = array(), $size = null)
	{
		return $this->make($user, $size);
	}
	
	/**
	 * Get and return html to display an avatar
	 * 
	 * @access private
	 * @param object $data[optional]
	 * @param string $size[optional]
	 * @param boolean $group[optional]
	 * @return string
	 */
	private function make($data = array(), $size = null, $group = false)
	{
		if ($size == null)
		{
			$size = $this->defaultSize;
		}
		
		$dir = null;
		if ($group) 
		{
			$field = 'name';
			$dir .= 'group_';
		}
		else 
		{
			$field = 'username';
			$dir .= 'user_';			
		}
		if (empty($data[$field]) || empty($data['id'])) 
		{
			$path = $this->defaultPath . '_' . $size . '.jpg';
		}
		else 
		{
			if (!empty($data['user_id'])) 
			{
				$dir .= $data['user_id'];
			}
			else 
			{
				$dir .= $data['id'];				
			}
			$path = '/uploads/' . $dir . '/' . $data[$field] . '_' . $size . '.jpg';
			if (!file_exists(WEBROOT . $path))
			{
				$path = '/uploads/' . $dir . '/' . $data[$field] . '_default.jpg';
			}
		}
		if (!file_exists(WEBROOT . $path)) 
		{
			if (file_exists(WEBROOT .  $this->defaultPath . '_' . $size . '.jpg'))
			{
				$path = $this->defaultPath . '_' . $size . '.jpg';	
				$return = '<img src="' . $path . '" width="'.$size.'" height="'.$size.'" alt="' . $data[$field] . '" />';
			}			
			else {
				$path = $this->defaultPath . '_48.jpg';	
				$return = '<img src="' . $path . '" width="'.$size.'" height="'.$size.'" alt="' . $data[$field] . '" />';
			}
		} else {
			$return = '<img src="' . $path . '" width="'.$size.'" height="'.$size.'" alt="' . $data[$field] . '" />';
		}
		return $return;
	}
	


}

?>