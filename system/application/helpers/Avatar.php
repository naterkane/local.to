<?php
/**
* Show an avatar
*/
class Avatar extends Html
{
	/**
	 * @var string $defaultPath
	 */
	public $defaultPath = '/img/avatar';
	
	/**
	 * 
	 * @return 
	 * @param object $user[optional]
	 */
	public function group($user = array(), $size = '48')
	{
		return $this->make($user, $size, true);
	}
	
	/**
	 * 
	 * @return 
	 * @param object $data[optional]
	 */
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
	
	/**
	 * 
	 * @return 
	 * @param object $user[optional]
	 */
	public function user($user = array(), $size = '48')
	{
		return $this->make($user, $size);
	}

}

?>