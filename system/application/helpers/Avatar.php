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
	 * @param array $group[optional]
	 * @param string $size[optional]
	 * @param boolean $link['optional']
	 * @return string
	 */
	public function group($group = array(), $size = null, $link = false)
	{
		return $this->make($group, $size, true, $link);
	}

	/**
	 * Get and return html to display the avatar of a user
	 * 
	 * @access public
	 * @param object $user[optional]
	 * @param string $size[optional]
	 * @param boolean $link['optional']
	 * @return string
	 */
	public function user($user = array(), $size = null, $link = false)
	{
		return $this->make($user, $size, false, $link);
	}
	
	/**
	 * Get and return html to display an avatar
	 * 
	 * @access private
	 * @param object $data[optional]
	 * @param string $size[optional]
	 * @param boolean $group[optional]
	 * @param boolean $link['optional']
	 * @return string
	 */
	private function make($data = array(), $size = null, $group = false, $link = false)
	{
		$return = "";
		$dir = "";
		$field = "";
		$path = "";
		$noCacheQuery = "";
		if ($size == null)
		{
			$size = $this->defaultSize;
		}
		if ($group != false) 
		{
			$field = 'id';
			$dir .= 'group_';
		}
		else 
		{
			if (empty($data['user_id'])) 
			{
				$field = 'id';
			}
			else 
			{
				$field = 'user_id';				
			}
			$dir .= 'user_';			
		}
		if (empty($data[$field]) || empty($data['id'])) 
		{
			$path = $this->defaultPath . '_' . $size . '.png';
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
			$path = '/uploads/' . $dir . '/' . $data[$field] . '_' . $size . '.png';
			
			if ((time() - filemtime(WEBROOT . $path)) < 129600) { // 24 hours
				
				$noCacheQuery = "?".filemtime(WEBROOT . $path) ."=";
			}
			
			//echo WEBROOT . $path.'<br/>';
			if (!file_exists(WEBROOT . $path))
			{
				$path = '/uploads/' . $dir . '/' . $data[$field] . '_default.jpg';
			} 
			//$path .= $noCacheQuery;
		}
		
		if (!file_exists(WEBROOT . $path)) 
		{
			if (file_exists(WEBROOT .  $this->defaultPath . '_' . $size . '.jpg'))
			{
				$path = $this->defaultPath . '_' . $size . '.jpg';	
				$path .= $noCacheQuery;
				$return .= '<img src="' . $path . '" width="'.$size.'" height="'.$size.'" alt="' . $data[$field] . '" />';
			}			
			else 
			{
				$path = $this->defaultPath . '_48.jpg';	
				$path .= $noCacheQuery;
				$return .= '<img src="' . $path . '" width="'.$size.'" height="'.$size.'" alt="' . $data[$field] . '" />';
			}
		} 
		else 
		{
			if ($link == true){
				//echo "link is true<br/>";
				$exts = array('png','jpg','gif','jpeg');
				foreach ($exts as $ext) {
					//echo WEBROOT .  '/uploads/' . $dir . '/' . $data[$field]. '_original.' . $ext."<br/>";
					if (file_exists(WEBROOT .   '/uploads/' . $dir . '/' . $data[$field]. '_original.' . $ext)):
						
						$return .= '<a href="/uploads/' . $dir . '/' . $data[$field]. '_original.' . $ext.$noCacheQuery.'">';
						break;
					endif;
				}
			}
			$return .= '<img src="' . $path . $noCacheQuery.'" width="'.$size.'" height="'.$size.'" alt="' . $data[$field] . '" />';
			if ($link == true) {
				$return .= "</a>";
			}
		}
		return $return;
	}
}
?>