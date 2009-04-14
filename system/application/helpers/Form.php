<?php
/**
* Form helper
*/
class Form extends Html
{
	
	/**
	* Print out an error to the user
	*
	* @access public	
	* @param string $name Name of field
	* @return string|null Error message
	*/
	function error($name)
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
	function getValue($name)
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
	function input($name, $options = array())
	{
		if (!isset($options['type'])) 
		{
			$options['type'] = 'input';
		}
		if (!isset($options['name'])) 
		{
			$options['name'] = $name;
		}
		if (!isset($options['id'])) 
		{
			$options['id'] = $name;
		}
		if (!isset($options['id'])) 
		{
			$options['id'] = $name;
		}	
		if (!isset($options['value'])) 
		{
			$options['value'] = $this->input->post($name);
		}
		if ((!isset($options['unsetPassword'])) && ($options['type'] == 'password')) 
		{
			$options['value'] = null;
		}		
		return sprintf($this->tags[$options['type']], $name, $this->_parseAttributes($options)); 
	}

}
?>