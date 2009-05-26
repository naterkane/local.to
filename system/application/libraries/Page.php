<?php
/**
* Paginator
*/
class Page
{
	
	public static $end = 1;
	public static $next;
	public static $nextText = 'more';	
	public static $offset = 20;	
	public static $page = 1;		
	public static $previousText;
	public static $showNext = false;
	public static $start = 0;
	
	
	public static function make($model, $data, $options = array())
	{
		if (!isset($options['method'])) 
		{
			$method = 'getMany';
		}
		else
		{
			$method = $options['method'];
		}
		$ci = get_instance();
		//if (count($data) <= self::$end) 
		//{
		//	return $data;
		//}
		$count = count($data) - 1;
		if ($count > self::$end) 
		{
			self::$showNext = true;
		}
		$return = $ci->$model->$method($data, self::$start, self::$end);
		unset($ci);
		//if (empty($return))
		//{
		//	show_404();
		//}
		//else 
		//{
			return $return;
		//}
	}

}
?>