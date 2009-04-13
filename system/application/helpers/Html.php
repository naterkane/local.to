<?php
/**
* HTML
*/
class Html
{

	/**
	 * html tags used by this helper.
	 *
	 * @var array
	 */
		var $tags = array(
			'meta' => '<meta%s/>',
			'metalink' => '<link href="%s"%s/>',
			'link' => '<a href="%s"%s>%s</a>',
			'mailto' => '<a href="mailto:%s" %s>%s</a>',
			'form' => '<form %s>',
			'formend' => '</form>',
			'input' => '<input name="%s" %s/>',
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

	/**
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
			$attribute = sprintf($attributeFormat, $key, ($escape ? h($value) : $value));
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
	 * @param  array  $exclude Array of options to be excluded.
	 * @param  string $insertBefore String to be inserted before options.
	 * @param  string $insertAfter  String to be inserted ater options.
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
	 * Convenience method for htmlspecialchars.
	 *
	 * @param string $text Text to wrap through htmlspecialchars
	 * @param string $charset Character set to use when escaping.  Defaults to config value in 'App.encoding' or 'UTF-8'
	 * @return string Wrapped text
	 * @link http://book.cakephp.org/view/703/h
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
	 * Message
	 *
	 * @access public
	 * @param $message $message
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
	 * @param  array   $htmlAttributes Array of HTML attributes.
	 * @param  string  $confirmMessage JavaScript confirmation message.
	 * @param  boolean $escapeTitle	Whether or not $title should be HTML escaped.
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

}
?>