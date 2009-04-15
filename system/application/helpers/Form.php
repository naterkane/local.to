<?php
/**
* Form helper
*/
class Form extends Html
{
	
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
			$options = $this->setOptions('value', $this->input->post($name), $options);
		}
		return sprintf($this->tags[$options['type']], $name, $this->_parseAttributes($options)); 
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
		$options = $this->setOptions('value', $this->input->post($name), $options);		
		return "<textarea name=\"" . $options['name'] . "\" id=\"" . $options['id'] . "\" rows=\"" . $options['rows']  . "\" cols=\"" . $options['cols'] . "\">" . $options['value'] . "</textarea>";
	}

}
?>