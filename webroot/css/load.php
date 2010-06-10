<?php

//
// Replace #FF0000 with the shorter one #F00 OR
// #558899 with #589 ?
// Could be slow (?)
//
// (true | false)
//
define('CSS_COLOR_COMPRESSION', true);

$load = trim(urldecode($_GET['load']));// file name
$ext = strtolower(trim(urldecode($_GET['ext'])));

//
// 0 =	no compression
// 1 =	remove tabs
// 2 =	remove new lines (\n and \r)
// 3 =	remove double spaces with single space,
//		more CSS compression (for CSS files)
//
define('_COMPRESS_', intval($_GET['compress']));

$load = preg_replace('/[^a-z0-9\-\_\;\/\.]/i', '', $load);
$ext = preg_replace('/[^a-z]/i', '', $ext);

if ('' == $load || ('css' != $ext && 'js' != $ext))
	die('');

$ctype = '';// content type
switch ($ext) {
	case 'js': $ctype = 'text/javascript';break;
	case 'css': $ctype = 'text/css';break;
	default: $ctype = 'text/plain';break;
}

header('Content-type: '.$ctype);

ob_start('ob_callback');
function ob_callback($buffer) {
	global $ext;

	if (_COMPRESS_ > 0) {
		$buffer = str_replace("\t", '', $buffer);
		if (_COMPRESS_ > 1) {
			$buffer = str_replace("\n", '', $buffer);
			$buffer = str_replace("\r", '', $buffer);
			if (_COMPRESS_ > 2) {
				$buffer = preg_replace('/( ){2,}/', ' ', $buffer);
				//$buffer = preg_replace('//', '', $buffer);
				if ('css' == $ext) {
					// CSS Compression
					$buffer = str_replace(';}', '}', $buffer);
					$buffer = preg_replace('/( )?\:( )?/', ':', $buffer);
					$buffer = preg_replace('/( )?\;( )?/', ';', $buffer);
					$buffer = preg_replace('/( )?\{( )?/', '{', $buffer);
					$buffer = preg_replace('/(\;)?( )?\}( )?/', '}', $buffer);
					//$buffer = str_replace(' {', '{', $buffer);
					$buffer = preg_replace('/font\-weight\:bold/i', 'font-weight:700', $buffer);// bold = 700

					if (CSS_COLOR_COMPRESSION) {
						if (preg_match_all('/color\:(#)?([a-f0-9]+){6}/i', $buffer, $m)) {
							$replaced = array();
							foreach ($m[0] as $color) {
								$color = preg_replace('/color:(#)?/i', '', $color);
								if (!in_array($color, $replaced)) {
									if ($color{0} == $color{1} && $color{2} == $color{3} && $color{4} == $color{5}) {
										$buffer = preg_replace('/color\:(#)?'.$color.'/i', 'color:#'.$color{0}.$color{2}.$color{4}, $buffer);
									}
									$replaced[] = $color;
								}
							}
							unset($m, $replaced);
						}
					}
				}
			}
		}
	}
    return trim($buffer);
}

$files = (strstr($load, ';')) ? explode(';', $load) : array($load);

unset($load);

foreach ($files as $f) {
	$f2 = $f . '.' . $ext;
	if (file_exists($f2))
		require($f2);
}
ob_end_flush();