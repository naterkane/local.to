<?php
/**
* Show an avatar
*/
class Avatar extends Html
{
	
	public $defaultPath = '/img/avatar';

	public function group($user = array(), $size = '48')
	{
		return $this->make($user, $size, true);
	}
	
	private function make($data = array(), $size = '48', $group = false)
	{
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
			$dir .= $data['id'];
			$path = '/uploads/' . $dir . '/' . $data[$field] . '_' . $size . '.jpg';
		}
		if (!file_exists(WEBROOT . $path)) 
		{
			$path = $this->defaultPath . '_' . $size . '.jpg';			
		}
		return '<img src="' . $path . '" alt="' . $data[$field] . '" />';
	}
	
	public function user($user = array(), $size = '48')
	{
		return $this->make($user, $size);
	}

}

?>