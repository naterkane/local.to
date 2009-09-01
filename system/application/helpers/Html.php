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
 * HTML Helper
 * 
 * Methods for displaying html elements
 * 
 * @package 	Nomcat
 * @subpackage	Helpers
 * @category	Helpers
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Html
{

	/**
	 * html tags used by this helper.
	 *
	 * @var array
	 */
	public $tags = array(
			'meta' => '<meta%s/>',
			'metalink' => '<link href="%s"%s/>',
			'link' => '<a href="%s"%s>%s</a>',
			'mailto' => '<a href="mailto:%s" %s>%s</a>',
			'form' => '<form %s>',
			'formend' => '</form>',
			'input' => '<input name="%s" %s/>',
			'upload' => '<input name="%s" %s/>',			
			'textarea' => '<textarea name="%s" %s>%s</textarea>',
			'hidden' => '<input type="hidden" name="%s" %s/>',
			'checkbox' => '<input type="checkbox" name="%s" %s/>',
			'checkboxmultiple' => '<input type="checkbox" name="%s[]"%s />',
			'radio' => '<input type="radio" name="%s" id="%s" %s />%s',
			'selectstart' => '<select name="%s"%s>',
			'selectmultiplestart' => '<select name="%s[]"%s>',
			'selectempty' => '<option value=""%s>&nbsp;</option>',
			'selectoption' => '<option value="%s"%s>%s</option>',
			'selectend' => '</select>',
			'optiongroup' => '<optgroup label="%s"%s>',
			'optiongroupend' => '</optgroup>',
			'checkboxmultiplestart' => '',
			'checkboxmultipleend' => '',
			'password' => '<input type="password" name="%s" %s/>',
			'file' => '<input type="file" name="%s" %s/>',
			'file_no_model' => '<input type="file" name="%s" %s/>',
			'submit' => '<input type="submit" %s/>',
			'submitimage' => '<input type="image" src="%s" %s/>',
			'button' => '<input type="%s" %s/>',
			'image' => '<img src="%s" %s/>',
			'tableheader' => '<th%s>%s</th>',
			'tableheaderrow' => '<tr%s>%s</tr>',
			'tablecell' => '<td%s>%s</td>',
			'tablerow' => '<tr%s>%s</tr>',
			'block' => '<div%s>%s</div>',
			'blockstart' => '<div%s>',
			'blockend' => '</div>',
			'tag' => '<%s%s>%s</%s>',
			'tagstart' => '<%s%s>',
			'tagend' => '</%s>',
			'para' => '<p%s>%s</p>',
			'parastart' => '<p%s>',
			'label' => '<label for="%s"%s>%s</label>',
			'fieldset' => '<fieldset%s>%s</fieldset>',
			'fieldsetstart' => '<fieldset><legend>%s</legend>',
			'fieldsetend' => '</fieldset>',
			'legend' => '<legend>%s</legend>',
			'css' => '<link rel="%s" type="text/css" href="%s" %s/>',
			'style' => '<style type="text/css"%s>%s</style>',
			'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
			'ul' => '<ul%s>%s</ul>',
			'ol' => '<ol%s>%s</ol>',
			'li' => '<li%s>%s</li>',
			'error' => '<div%s>%s</div>'
		);
	public $testingData;
	public $validationErrors = array();

	/**
	 * Construct
	 * Passes testing data, form data, timezones, and validation errors
	 */
	function __construct()
	{
		$ci = get_instance();
		$this->testingData = $ci->testingData;
		if (isset($ci->input)) 
		{
			$this->input = $ci->input;
		}
		if (!empty($ci->postData)) 
		{
			$this->data = $ci->postData;
		}
		else
		{
			$this->data = $ci->data;
		}
		$this->timeZones = $ci->User->timeZones;
		$this->validationErrors = $ci->validationErrors;		
		unset($ci);
	}
	

	/**
	 * Format the element attributes
	 * @param  string $key
	 * @param  string $value
	 * @return string
	 * @access private
	 */
	function __formatAttribute($key, $value, $escape = true) {
		$attribute = '';
		$attributeFormat = '%s="%s"';
		$minimizedAttributes = array('compact', 'checked', 'declare', 'readonly', 'disabled', 'selected', 'defer', 'ismap', 'nohref', 'noshade', 'nowrap', 'multiple', 'noresize');
		if (is_array($value)) {
			$value = '';
		}

		if (in_array($key, $minimizedAttributes)) {
			if ($value === 1 || $value === true || $value === 'true' || $value == $key) {
				$attribute = sprintf($attributeFormat, $key, $key);
			}
		} else {
			$attribute = sprintf($attributeFormat, $key, ($escape ? $this->encode($value) : $value));
		}
		return $attribute;
	}

	/**
	 * Returns a space-delimited string with items of the $options array. If a
	 * key of $options array happens to be one of:
	 *	+ 'compact'
	 *	+ 'checked'
	 *	+ 'declare'
	 *	+ 'readonly'
	 *	+ 'disabled'
	 *	+ 'selected'
	 *	+ 'defer'
	 *	+ 'ismap'
	 *	+ 'nohref'
	 *	+ 'noshade'
	 *	+ 'nowrap'
	 *	+ 'multiple'
	 *	+ 'noresize'
	 *
	 * And its value is one of:
	 *	+ 1
	 *	+ true
	 *	+ 'true'
	 *
	 * Then the value will be reset to be identical with key's name.
	 * If the value is not one of these 3, the parameter is not output.
	 *
	 * @param  array  $options Array of options.
	 * @param  array  $exclude[optional] Array of options to be excluded.
	 * @param  string $insertBefore[optional] String to be inserted before options.
	 * @param  string $insertAfter[optional]  String to be inserted ater options.
	 * @return string
	 */
	function _parseAttributes($options, $exclude = null, $insertBefore = ' ', $insertAfter = null) {
		if (is_array($options)) {
			$options = array_merge(array('escape' => true), $options);

			if (!is_array($exclude)) {
				$exclude = array();
			}
			$keys = array_diff(array_keys($options), array_merge((array)$exclude, array('escape')));
			$values = array_intersect_key(array_values($options), $keys);
			$escape = $options['escape'];
			$attributes = array();

			foreach ($keys as $index => $key) {
				$attributes[] = $this->__formatAttribute($key, $values[$index], $escape);
			}
			$out = implode(' ', $attributes);
		} else {
			$out = $options;
		}
		return $out ? $insertBefore . $out . $insertAfter : '';
	}

	/**
	 * Make a delete message link
	 *
	 * @access public
	 * @param array $message
	 * @param boolean isOwner
	 * @return 
	 */
	public function deleteMessageLink($message = array(), $isOwner = false)
	{
		if ($isOwner && !$message['deleted_by_user']) 
		{
			return "[" . $this->link('Delete', '/messages/delete/' . $message['id'] . $this->sendMeHere(), array('id'=>'delete_' . $message['id']), 'Are your sure you want to delete this message? This can not be undone.') . "]";
		}
	}

	/**
	 * Make a favorite link
	 *
	 * @access public
	 * @param array $message
	 * @param array $user	
	 * @return 
	 */
	public function favoriteLink($message = array(), $user = array())
	{
		if (!$user) 
		{
			return;
		}
		$return = "[";
		if (in_array($message['id'], $user['favorites'])) 
		{
			$return .= $this->link('Unfavorite', '/messages/unfavorite/' . $message['id'] . $this->sendMeHere(), array('class'=>'on', 'id'=>'favorite_link_' . $message['id']));
		}
		else 
		{
			$return .= $this->link('Favorite', '/messages/favorite/' . $message['id'] . $this->sendMeHere(), array('class'=>'off', 'id'=>'favorite_link_' . $message['id']));
		}
		$return .= "]";
		return $return;
	}

	/**
	 * Convenience method for htmlspecialchars.
	 *
	 * @param string $text Text to wrap through htmlspecialchars
	 * @param string $charset[optional] Character set to use when escaping.  Defaults to config value in 'App.encoding' or 'UTF-8'
	 * @return string Wrapped text
	 */
	function encode($text, $charset = null) {
		if (is_array($text)) {
			return array_map('h', $text);
		}
		if (empty($charset)) {
			$charset = 'UTF-8';
		}
		return htmlspecialchars($text, ENT_QUOTES, $charset);
	}

	/**
	 * Output group name 
	 *
	 * @access public
	 * @param array $group Group data
	 * @return string
	 */
	public function groupName($group=array())
	{
		if (!is_array($group)) 
		{
			return $group;
		}
		if (isset($group['fullname'])) 
		{
			return $group['fullname'];
		}
		if (isset($group['name'])) 
		{
			return $group['name'];
		}
	}	
	
	/**
	 * compares a string against the request URI
	 * @return 
	 * @access public
	 * @param string $string
	 */
	public function isSection($string)
	{
		return stristr($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $string);
	}
	
	/**
	 * Output a message replaces links with anchor html
	 *
	 * @access public
	 * @param $message[optional] $message
	 * @return $message with tags processed	
	 */
	function message($message = null)
	{
		$message = preg_replace(MESSAGE_MATCH, "'\\1@' . \$this->link('\\2', '\\2')", $message);
		return $message;
	}

	/**
	 * Creates an HTML link.
	 *
	 * If $url starts with "http://" this is treated as an external link. Else,
	 * it is treated as a path to controller/action and parsed with the
	 * HtmlHelper::url() method.
	 *
	 * If the $url is empty, $title is used instead.
	 *
	 * @param  string  $title The content to be wrapped by <a> tags.
	 * @param  mixed   $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
	 * @param  array   $htmlAttributes[optional] Array of HTML attributes.
	 * @param  string  $confirmMessage[optional] JavaScript confirmation message.
	 * @param  boolean $escapeTitle[optional]	Whether or not $title should be HTML escaped.
	 * @return string	An <a /> element.
	 */	
	function link($title, $url, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true)
	{

		if (isset($htmlAttributes['escape'])) {
			$escapeTitle = $htmlAttributes['escape'];
			unset($htmlAttributes['escape']);
		}

		if ($escapeTitle === true) {
			$title = $this->encode($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($htmlAttributes['confirm'])) {
			$confirmMessage = $htmlAttributes['confirm'];
			unset($htmlAttributes['confirm']);
		}
		if ($confirmMessage) {
			$confirmMessage = str_replace("'", "\'", $confirmMessage);
			$confirmMessage = str_replace('"', '\"', $confirmMessage);
			$htmlAttributes['onclick'] = "return confirm('{$confirmMessage}');";
		} elseif (isset($htmlAttributes['default']) && $htmlAttributes['default'] == false) {
			if (isset($htmlAttributes['onclick'])) {
				$htmlAttributes['onclick'] .= ' event.returnValue = false; return false;';
			} else {
				$htmlAttributes['onclick'] = 'event.returnValue = false; return false;';
			}
			unset($htmlAttributes['default']);
		}
		return sprintf($this->tags['link'], $url, $this->_parseAttributes($htmlAttributes), $title); 
	}

	/**
	 * Output a menu item
	 *
	 * @param  string  $title The content to be wrapped by <a> tags.
	 * @param  mixed   $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
	 * @param  array   $htmlAttributes[optional] Array of HTML attributes.
	 * @param  string  $confirmMessage[optional] JavaScript confirmation message.
	 * @param  boolean $escapeTitle[optional]	Whether or not $title should be HTML escaped.
	 * @return string	An <a /> element wrapped in a formatted <li>
	 */
	public function menuItem($title, $url, $count = null, $linkAttributes = array(), $confirmMessage = false, $escapeTitle = true)
	{
		$return = "<li ";
		if ($this->isSection($url)) 
		{
			$return .= "class=\"current\"";
		}
		$return .= ">";
		if (is_array($count)) 
		{
			$count = count($count);
			if ($count > 0) 
			{
				$title .= " (" . $count . ")";
			}
		}
		elseif(is_int($count) && $count > 0) {
				$title .= " (" . $count . ")";			
		}		
		$return .= $this->link($title, $url, $linkAttributes, $confirmMessage, $escapeTitle);
		$return .= "</li>\n";
		return $return;
	}
	

	/**
	 * Output user name 
	 *
	 * @access public
	 * @param array $user User data
	 * @return string
	 */
	public function name($user=array())
	{
		if (!is_array($user)) 
		{
			return $user;
		}
		if (isset($user['realname'])) 
		{
			return $user['realname'];
		}
		if (isset($user['username'])) 
		{
			return $user['username'];
		}
	}
	
	/**
	 * Make a reply link
	 *
	 * @access public
	 * @param array $message
	 * @return 
	 */
	public function replyLink($message = array())
	{
		if ($message['reply_to']) 
		{
			$reply = $message['reply_to'];
		} 
		else 
		{
			$reply = $message['id'];
		}
		$url = null;		
		if ($_SERVER['PATH_INFO']) 
		{
			$parts = explode('/', $_SERVER['PATH_INFO']);
			if ((!empty($parts[1])) && (strtolower($parts[1]) == 'home'))
			{
				$url = '/home';
			}
		}
		return "[" . $this->link('Reply', '/home/' . $reply . $this->sendMeHere($url, true), array('id'=>'reply_link_' . $message['id'])) . "]";
	}
	
	
	/**
	 * Create a redirect query string to send a user back to requesting page
	 *
	 * @access public
	 * @param string $url[optional] To override default
	 * @return string '?redirect=' followed by encoded list
	 */
	public function sendMeHere($url = null, $pathOnly = false)
	{
		if (!$url) 
		{
			if ($pathOnly) 
			{
				$url = $_SERVER['PATH_INFO'];
			} 
			else 
			{
				$url = $_SERVER['REQUEST_URI'];
			}
		}
		return '?redirect=' . urlencode($url);
	}
	
	/**
	 * Calculate unread messages
	 *
	 * @access public
	 * @param array $user
	 * @param string $counter	
	 * @return int | null
	 */
	public function unread($user = array(), $counter = null)
	{
		$read_counter = $counter . '_read';
		if (array_key_exists($counter, $user) && array_key_exists($read_counter, $user)) 
		{
			$difference = count($user[$counter]) - $user[$read_counter];
			if ($difference > 0) 
			{
				return $difference;
			}
		}
		return null;
	}
	
	/**
	 * Calculate unread group messages
	 *
	 * @access public
	 * @param array $user
	 * @param array $group	
	 * @return int | null
	 */
	public function unreadGroup($user = array(), $group = null)
	{
		$counter = 'group_messages_read';
		$group_name = $group['name'];
		if (array_key_exists($group_name, $user[$counter]) && !empty($group['messages'])) 
		{
			$difference = count($group['messages']) - $user[$counter][$group_name];
			if ($difference > 0) 
			{
				return $difference;
			}
		}
		return null;
	}
	
	
}
?>