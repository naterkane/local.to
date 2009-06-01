<?php
/**
* Show an avatar
*/
class Avatar extends Html
{
	
	public $defaultPath = '/img/avatar';
	
	public function show($user = array(), $size = '48')
	{
		if (empty($user['username']) || empty($user['id'])) 
		{
			$path = $this->defaultPath . '_' . $size . '.jpg';
		}
		else 
		{
			$path = '/uploads/' . $user['id'] . '/' . $user['username'] . '_' . $size . '.jpg';
		}
		if (!file_exists(WEBROOT . $path)) 
		{
			$path = $this->defaultPath . '_' . $size . '.jpg';			
		}
		return '<img src="' . $path . '" alt="' . $user['username'] . '" />';
	}

}

?>