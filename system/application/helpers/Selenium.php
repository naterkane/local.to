<?php
/**
* Selenium helper
*/
class Selenium
{

	public $admin_url;
	public $badRequest = 'Bad Request';
	public $badCharacters = 'The URI you submitted has disallowed characters.';
	public $errorText = 'An Error Was Encountered';	
	public $missingText = '404 Page Not Found';	
	public $noticeText = 'Notice';	
	public $phpError = 'A PHP Error was encountered';	
	public $url;	
	public $warningText = 'Warning';

	public function __construct()
	{
		$ci = get_instance();
		$this->url = $ci->config->item('base_url');
		$this->admin_url = $ci->config->item('admin_base_url');	
		unset($ci);
	}
	
	/**
	 * ie Xpath fix
	 *
	 * @access private	
	 * @param string $value
	 * @return string $value
	 */	
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
	 * @access private	
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
	 * @access public		
	 * @param string $title Name of suite
	 * @param string $view View to load
	 */
	public function addTestCase($title, $view)
	{
		echo '<tr><td><a href="' . $view . '" target="testFrame">' . $title . '</a></td></tr>';
	}
	
	/**
	 * Check all errors
	 *
	 * @access public	 
	 */
	public function checkErrors()
	{
		$this->write('verifyTextNotPresent', $this->noticeText);
		$this->write('verifyTextNotPresent', $this->warningText);
		$this->write('verifyTextNotPresent', $this->missingText);
		$this->write('verifyTextNotPresent', $this->phpError);
		$this->write('verifyTextNotPresent', $this->errorText);
		$this->write('verifyTextNotPresent', $this->badCharacters);	
		$this->write('verifyTextNotPresent', $this->badRequest);				
	}
	
	/**
	 * Test that a bad characters error was thrown
	 *
	 * @access public
	 * @param string $url
	 * @return 
	 */
	public function badChar($url)
	{
		$this->write('openAndWait', $url);		
		$this->write('verifyTextPresent', $this->badCharacters);	
	}
	
	/**
	 * Outputs the title of the test case.
	 *
	 * @param string $title Title of test case
	 * @access public	
	 */
	public function caseTitle($title)
	{
		echo '<tr><td rowspan="1" colspan="3">'.$title.'</td></tr>';
	}

	/**
	 * Click a button and wait for response
	 *
	 * @access public			
	 * @param string $buttonValue e.g. 'save'
	 */
	public function click($buttonValue=null) 
	{
		$this->write('clickAndWait', '//input[@value=\'' . $buttonValue . '\']');
		$this->checkErrors();
	}

	/**
	* Used for testing non-public pages
	*
	* @access public	
	* @param string $path
	* @return
	*/
	public function mustBeLoggedIn($path)
	{
		$this->write('openAndWait', $path);
		$this->write('verifyTextPresent', 'Sign In');
	}

	/**
	 * Open page
	 *
	 * Includes calls to several methods useful to check if a page is loading properly
	 * 
	 * @access public	
	 * @param string $path Relative path to open
	 */
	public function openPage($page)
	{
		$this->write('openAndWait', $page);
		$this->checkErrors();
	}

	/**
	* Random Alpha-Numeric String
	*
	* @param int length
	* @return string 
	* @access public
	*/
	public function randomString($length) {
		$randstr = null;
		srand();
		$chars = array( 'a','b','c','d','e','f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A','B','C','D','E','F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		for ($rand = 0; $rand < $length; $rand++) {
			$random = rand(0, count($chars) -1);
			$randstr .= $chars[$random];
		}
		return $randstr;
	}

	/**
	* Random Alpha-Numeric String
	* 
	* @param string $name
	* @param string $password	
	* @return
	* @access public
	*/
	public function signIn($name, $password)
	{
		$this->openPage('/signin');
		$this->write('type', 'username', $this->randomString(10));
		$this->write('type', 'password', $this->randomString(10));		
		$this->click('Sign In');
		$this->write('verifyTextPresent', 'The username and password do not match any in our records.');		
		$this->write('type', 'username', $name);
		$this->write('type', 'password', $password);	
		$this->click('Sign In');
		$this->write('verifyText', 'profile_username', $name);
	}

	/**
	* Wrapper for signing out
	*
	* @access public	
	*/
	public function signOut()
	{
		$this->openPage('/signout');
		//$this->write('verifyTextPresent', 'You have successfully signed out.');	
	}

	/**
	* Sign In
	*
	* @todo This will expanded into its own test with validation
	* @param string $name
	* @param string $password	
	* @return
	* @access public
	*/	
	public function signUp($name, $password, $email)
	{
		$this->openPage('/admin/create_invite');
		$this->write('storeValue', 'email', 'invite_email');	
		$this->write('storeValue', 'key', 'invite_key');		
		$this->openPage('/signup/${invite_email}/${invite_key}');
		$this->write('type', 'username', $name);
		$this->write('type', 'password', $password);
		$this->write('type', 'passwordconfirm', $password);	
		$this->write('type', 'email', $email);
		$this->click('Sign Up');
		$this->write('verifyTextPresent', 'Your account has been created. Please sign in.');		
		$this->openPage('/admin/delete_invite/${invite_email}/${invite_key}');		
	}
	
	/**
	 * Outputs the title of the test suite. There is no output if the constant ALL_SUITE is defined. 
	 * 
	 * @param string $title Title of suite
	 * @access public	
	 * @return
	 */
	public function suiteTitle($title)
	{
		echo '<tr><td><b>'.$title.'</b></td></tr>';
	}
	
	/**
	 * Write a test command
	 *
	 * @access public
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