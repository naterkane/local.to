<?php
/**
* Form helper
*/
class Form extends Html
{

	public $data = array();
	public $validationErrors = array();	

	function __construct()
	{
		$ci = get_instance();
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
	* Print out a checkbox
	*
	* @access public	
	* @param string $name Name of field
	* @param array $options[optional] HTML options
	* @return string
	*/
	public function checkbox($name, $options = array())
	{
		$options = $this->setOptions('type', 'checkbox', $options);
		$options = $this->setOptions('name', $name, $options);
		$options = $this->setOptions('id', $name, $options);
		if (empty($options['value'])) {
			$options = $this->setOptions('value', $this->getElementValue($name), $options);
		}
		if($options['value'] == 1) {
			$options['checked'] = 'checked';
		} else {
			$options['value'] = 1;
		}
		$output = $this->input($name, array('type' => 'hidden', 'id' => $options['id'] . '_', 'name' => $options['name'], 'value' => '0'));
		return $output . sprintf($this->tags[$options['type']], $name, $this->_parseAttributes($options)); 
	}
	
	
	/**
	 * Process form options
	 *
	 * @access private
	 * @param string
	 * @return 
	 */
	private function setOptions($field, $default, $options)
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
		$options = $this->setOptions('name', $name, $options);
		$options = $this->setOptions('id', $name, $options);
		if ((!isset($options['unsetPassword'])) && ($options['type'] == 'password')) 
		{
			$options = $this->setOptions('value', null, $options);
		}
		else
		{
			$options = $this->setOptions('value', $this->getElementValue($name), $options);			
		}
		return sprintf($this->tags[$options['type']], $name, $this->_parseAttributes($options)); 
	}

	function select($name, $values, $options=array())
	{
		$fieldValue = $this->getElementValue($name);
		$return = "<select id=\"$name\" name=\"$name\">\n";
		if (empty($options['no_blank'])) 
		{
			$return .= "<option value=\"\">&nbsp</option>\n";
		}
		foreach ($values as $key => $value) {
			$return .= 	"<option value=\"$key\"";
			if ($fieldValue == $key) 
			{
				$return .= 	" selected=\"selected\" ";
			}
			$return .= ">$value</option>\n";
		}
		$return .= "</select>\n";		
		return $return;
	}

	/**
	 * Creates a textarea 
	 *
	 * @param string $fieldName 
	 * @param array $options Array of HTML attributes.
	 * @return string An HTML text input element
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
	 * @param string
	 * @return 
	 */
	function timezones($name, $options = array())
	{
		$options['no_blank'] = true;
		return $this->select($name, $this->timeZones, $options);
	}
	

}
?>