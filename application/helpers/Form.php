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
 * Form Helper
 * 
 * Methods for displaying form elements
 * 
 * @package 	Nomcat
 * @subpackage	Helpers
 * @category	Helpers
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Form extends Html
{
	/**
	 * Array of data to be passed to the form
	 * @var array
	 */
	public $data = array();

	/**
	 * Array of all errors passed from the model
	 * @var array
	 */
	public $validationErrors = array();	

	/**
	 * Calls parent constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	* Print out a checkbox
	*
	* @access public	
	* @param string $name Name of field
	* @param array $options[optional] HTML options
	* @return string
	*/
	public function checkbox($name, $options = array())
	{
		$options = $this->setOptions('id', $name, $options);
		if (empty($options['value'])) {
			$options = $this->setOptions('value', $this->getElementValue($name), $options);
		}
		if($options['value'] == 1) {
			$options['checked'] = 'checked';
		} else {
			$options['value'] = 1;
		}
		$output = $this->input($name, array('type' => 'hidden', 'id' => $options['id'] . '_', 'name' => $name, 'value' => '0'));
		return $output . sprintf($this->tags['checkbox'], $name, $this->_parseAttributes($options)); 
	}
	
	
	/**
	 * Process form options
	 *
	 * @access private
	 * @param string $field
	 * @param string $default
	 * @param array $options		
	 * @return array|string
	 */
	private function setOptions($field, $default, $options = array())
	{
		if (!isset($options[$field])) 
		{
			$options[$field] = $default;
		}
		return $options;
	}
	
	/**
	 * Get a value for a form element
	 *
	 * @access private
	 * @param string Name	
	 * @return mixed
	 */	
	private function getElementValue($name)
	{
		$value = $this->input->post($name);
		if (!$value) 
		{
			if (isset($this->data[$name])) 
			{
				$value = $this->data[$name];
			}
		}
		return $value;
	}

	/**
	 * Print out an error to the user
	 *
	 * @access public	
	 * @param string $name Name of field
	 * @return string|null Error message
	 */
	public function error($name)
	{
		$return = null;
		if (isset($this->validationErrors[$name])) 
		{
			$return .= "<div class=\"inline-error\">" . $this->validationErrors[$name] . "</div>\n";
		}
		return $return;
	}
	
	/**
	 * Get a value from a post field
	 *
	 * @access public	
	 * @param string $name Name of field
	 * @return string|null Value
	 */
	public function getValue($name)
	{
		if (isset($this->postValue[$name])) 
		{
			return $this->postValue[$name];
		}
	}

	/**
	 * Print out an input field
	 *
	 * @access public	
	 * @param string $name Name of field
	 * @param array $options[optional] HTML options
	 * @return string
	 */
	public function input($name, $options = array())
	{
		$options = $this->setOptions('type', 'input', $options);
		$options = $this->setOptions('id', $name, $options);
		if ((!isset($options['unsetPassword'])) && ($options['type'] == 'password')) 
		{
			$options = $this->setOptions('value', null, $options);
		}
		else
		{
			$options = $this->setOptions('value', $this->getElementValue($name), $options);			
			$options['value'] = html_entity_decode($options['value']);
		}
		$type = $options['type'];
		unset($options['type']);
		return sprintf($this->tags[$type], $name, $this->_parseAttributes($options)); 
	}

	/**
	 * Return the HTML for optgroup
	 * 
	 * @param string $name
	 * @param array $values
	 * @param array $options[optional]
	 * @return string And HTML select element
	 */
	function optgroup($name, $values, $options=array())
	{
		$fieldValue = $this->getElementValue($name);
		$return = "<select id=\"$name\" name=\"$name\">\n";
		if (empty($options['first_label'])) 
		{
			$return .= "<option value=\"\">&nbsp;</option>\n";
		}
		else 
		{
			$return .= "<option value=\"\">" . $options['first_label'] . "</option>\n";			
		}
		foreach ($values as $optgroup) {
			foreach ($optgroup as $title => $values) {
				$return .= "<optgroup label=\"$title\">\n";
				foreach ($values as $key => $value) {
					$return .= $this->makeOptionTag($fieldValue, $key, $value);					
				}
			}
			$return .= "</optgroup>\n";			
		}
		$return .= "</select>\n";		
		return $return;
	}
	
	/**
	 * Return the HTML for a select field
	 * 
	 * @param string $name
	 * @param array $values
	 * @param array $options[optional]
	 * @return string And HTML select element
	 */
	function select($name, $values, $options=array())
	{
		$fieldValue = $this->getElementValue($name);
		$return = "<select id=\"$name\" name=\"$name\">\n";
		if (empty($options['no_blank'])) 
		{
			$return .= "<option value=\"\">&nbsp;</option>\n";
		}
		foreach ($values as $key => $value) {
			$return .= $this->makeOptionTag($fieldValue, $key, $value);
		}
		$return .= "</select>\n";		
		return $return;
	}
	
	/**
	 * Make an option tag
	 * 
	 * @param string $fieldValue
	 * @param string $key
	 * @param string $value
	 * @access public
	 * @return string
	 */	
	public function makeOptionTag($fieldValue, $key, $value)
	{
		$return = "<option value=\"$key\"";
		if (($fieldValue == $key) && ($fieldValue)) 
		{
			$return .= 	" selected=\"selected\" ";
		}
		$return .= ">$value</option>\n";
		return $return;
	}

	/**
	 * Create a hidden testing field only visible when testing
	 *
	 * @access public
	 * @param string fieldName
	 * @return boolean|string
	 */
	function testInput($fieldName = null)
	{
		if (!empty($this->testingData)) 
		{
			return $this->input('testing_' . $fieldName, array('value'=>$this->testingData[$fieldName], 'type'=>'hidden'));
		}
		return false;
	}

	/**
	 * Creates a textarea 
	 *
	 * @param string $name 
	 * @param array $options Array of HTML attributes.
	 * @access public
	 * @return string An HTML textarea element
	 */
	public function textarea($name, $options = array()) 
	{
		$options = $this->setOptions('name', $name, $options);
		$options = $this->setOptions('id', $name, $options);		
		$options = $this->setOptions('rows', 8, $options);		
		$options = $this->setOptions('cols', 20, $options);	
		$options = $this->setOptions('value', $this->getElementValue($name), $options);		
		return "<textarea name=\"" . $options['name'] . "\" id=\"" . $options['id'] . "\" rows=\"" . $options['rows']  . "\" cols=\"" . $options['cols'] . "\">" . $options['value'] . "</textarea>";
	}

	/**
	 * Timezone select
	 *
	 * @access public
	 * @param string $name
	 * @param array $options[optional]
	 * @return string An HTML select element
	 */
	function timezones($name, $options = array())
	{
		$options['no_blank'] = true;
		return $this->select($name, $this->timeZones, $options);
	}

}