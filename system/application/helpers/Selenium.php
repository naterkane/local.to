<?php
/**
* Selenium helper
*/
class Selenium
{

	private $errorText = 'An Error Was Encountered';	
	private $missingText = '404 Page Not Found';	
	private $noticeText = 'Notice';	
	private $warningText = 'Warning';
	private $phpError = 'A PHP Error was encountered';
	
	
	private function ieXpathFix($value)
    {
		// Check if the value is ment to be an xpath	    
		if ((strpos($value, 'xpath=')===0) || (strpos($value, '//')===0))
		{
			$regex = "/id\('(.+)'\)/iUs";
			$replace = "//*[@id='\\1']";                

			$value = preg_replace($regex, $replace, $value);
			return $value;
		}
		else 
		{
			return $value; // If not return the value without modifications
		}
    }
	
	/**
	 * Write a test command
	 *
	 * @param string $command Command name. Find them here: http://seleniumhq.org/documentation/core/reference.html
	 * @param string $target Name of object to target
	 * @param string $value Value to be passed
	 */
	private function row($command, $target, $value)
	{
	    $value2 = $this->ieXpathFix($target); 
	    $value3 = $this->ieXpathFix($value); 
		return '<tr><td>' . $command . '</td><td>' . $target . '</td><td>' . $value . '</td></tr>';
	}
	
	/**
	 * Adds a testcase to a suite.
	 *
	 * @param string $title Name of suite
	 * @param string $view View to load
	 */
	public function addTestCase($title, $view)
	{
		echo '<tr><td><a href="' . $view . '" target="testFrame">' . $title . '</a></td></tr>';
	}
	
	/**
	 * Open page
	 *
	 * Includes calls to several methods useful to check if a page is loading properly
	 * 
	 * @param string $path Relative path to open
	 */
	public function openPage($page)
	{
		$this->write('open', $page);
		$this->write('verifyTextNotPresent', $this->noticeText);
		$this->write('verifyTextNotPresent', $this->warningText);
		$this->write('verifyTextNotPresent', $this->missingText);
		$this->write('verifyTextNotPresent', $this->phpError);
		$this->write('verifyTextNotPresent', $this->errorText);
	}
	
	/**
	 * Outputs the title of the test suite. There is no output if the constant ALL_SUITE is defined. 
	 * 
	 * @param string $title Title of suite
	 * @return
	 */
	public function suiteTitle($title)
	{
		echo '<tr><td><b>'.$title.'</b></td></tr>';
	}
	
	/**
	 * Outputs the title of the test case.
	 *
	 * @param string $title Title of test case
	 */
	public function caseTitle($title)
	{
		echo '<tr><td rowspan="1" colspan="3">'.$title.'</td></tr>';
	}
	
	/**
	 * Write a test command
	 *
	 * @param string $command Command name. Find them here: http://seleniumhq.org/documentation/core/reference.html
	 * @param string $target Name of object to target
	 * @param string $value Value to be passed
	 */
	public function write($command, $target = '&nbsp;', $value = '&nbsp;')
	{
		echo $this->row($command, $target, $value);
	}

}
?>