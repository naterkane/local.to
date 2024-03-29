<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
 /**
 * Selenium Helper
 *
 * @package     Nomcat
 * @subpackage  Helpers
 * @category    Helpers
 */
class Selenium
{

	/**
	 * Url for admin
	 *
	 * @var string
	 */
	public $admin_url;
	/**
	 * Text to be verified for a bad request error
	 *
	 * @var string
	 */	
	public $badRequest = 'Bad Request';
	/**
	 * Text to be verified for a bad character error
	 *
	 * @var string
	 */	
	public $badCharacters = 'The URI you submitted has disallowed characters.';
	/**
	 * Text to be verified for a general error
	 *
	 * @var string
	 */
	public $errorText = 'An Error Was Encountered';	
	/**
	 * Text to be verified for a 404
	 *
	 * @var string
	 */	
	public $missingText = '404 Page Not Found';	
	/**
	 * Text to be verified for a PHP notice
	 *
	 * @var string
	 */	
	public $noticeText = 'Notice';	
	/**
	 * Text to be verified for a PHP error
	 *
	 * @var string
	 */	
	public $phpError = 'A PHP Error was encountered';	
	/**
	 * Url to be checked
	 *
	 * @var string
	 */	
	public $url;	
	/**
	 * Text to be verified for a php warning
	 *
	 * @var string
	 */	
	public $warningText = 'Warning';

	/**
	 * Class constructor
	 * Passes off url and admin urls from a controller instance
	 * @access public
	 * @return 	
	 */
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
	 * @return 	
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
	 * @return 	
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
	 * @return 	
	 */
	public function addTestCase($title, $view)
	{
		echo '<tr><td><a href="' . $view . '" target="testFrame">' . $title . '</a></td></tr>';
	}
	
	/**
	 * Check all errors
	 * Checks for php notices, warnings, errors. Also code igniter bad characters error and bad request errors.
	 * @param boolean $missing check for 404 error
	 * @access public	 
	 * @return 	
	 */
	public function checkErrors($missing = true)
	{
		$this->write('verifyTextNotPresent', $this->noticeText);
		$this->write('verifyTextNotPresent', $this->warningText);
		$this->write('verifyTextNotPresent', $this->phpError);
		$this->write('verifyTextNotPresent', $this->errorText);
		$this->write('verifyTextNotPresent', $this->badCharacters);	
		$this->write('verifyTextNotPresent', $this->badRequest);
		if ($missing) 
		{
			$this->write('verifyTextNotPresent', $this->missingText);
		}
	}
	
	/**
	 * Test that a bad characters error was thrown
	 * Used to test for bad characters in URL. Is true when a bad character is present.
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
	 * @return 	
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
	 * @return 	
	 */
	public function click($buttonValue=null) 
	{
		$this->write('clickAndWait', '//input[@value=\'' . $buttonValue . '\']');
		$this->checkErrors();
	}

	/**
	 * Check group profile
	 *
	 * @access public
	 * @param string $page Path to page to open
	 * @param boolean $signedIn Set to false if testing non-signed in page	
	 * @param boolean $showSignUp Set to false if testing for sign up form
	 * @return 
	 */
	public function groupSbMember($page, $showSignIn = false, $showSignUp = false)
	{
		$this->openPage($page);
		if ($showSignIn) 
		{
			$this->write('verifyElementPresent', 'sb_signin');			
		}
		if ($showSignUp) 
		{
			$this->write('verifyElementPresent', 'sb_signup');			
		}
		$this->write('verifyElementPresent', 'sb_group');		
		$this->write('verifyElementPresent', 'sb_group_profile');
		$this->write('verifyElementPresent', 'sb_members');		
		$this->write('verifyElementPresent', 'sb_menu_long');
	}
	
	/**
	 * Check group profile
	 *
	 * @access public
	 * @param string $page
	 * @param boolean $signedIn Set to false if testing non-signed in page	
	 * @param boolean $showSignUp Set to false if testing for sign up form	
	 * @return 
	 */
	public function groupSbNonMember($page, $showSignIn = false, $showSignUp = false)
	{
		$this->openPage($page);
		if ($showSignIn) 
		{
			$this->write('verifyElementPresent', 'sb_signin');			
		}
		if ($showSignUp) 
		{
			$this->write('verifyElementPresent', 'sb_signup');			
		}
		$this->write('verifyElementPresent', 'sb_group');		
		$this->write('verifyElementPresent', 'sb_group_profile');
		$this->write('verifyElementPresent', 'sb_members');		
		$this->write('verifyElementNotPresent', 'sb_menu_long');
	}	

	/**
	 * Make a group and open member page
	 *
	 * @access public
	 * @param string $group Name of group to make
	 * @return 
	 */
	public function makeGroup($group)
	{
		$this->openPage('/groups/add');
		$this->write('type', 'name', $group);
		$this->write('type', 'fullname', $group);	
		$this->click('Add');
		$this->write('verifyTextPresent', $group);
		$this->openPage('/groups/members/' . $group);
	}

	/**
	* Can user view page not signed in?
	* Used for testing non-public pages to make sure user can not access when not signed in
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
	 * Open page that should not exist
	 * Make sure a 404 appears
	 * 
	 * @access public	
	 * @param string $path Relative path to open
	 */
	public function open404($page)
	{
		$this->write('openAndWait', $page);
		$this->checkErrors(false);
	}

	/**
	 * Open page
	 * Opens page, waits to load, and then checks for all errors.
	 * 
	 * @access public	
	 * @param string $path Relative path to open e.g. '/home'
	 */
	public function openPage($page)
	{
		$this->write('openAndWait', $page);
		$this->checkErrors();
	}

	/**
	* Random Alpha-Numeric String
	* Used for generating random user names, group names, messages, etc.
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
	* Sign in a user
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
	* sign out a user
	*
	* @access public
	* @return 	
	*/
	public function signOut()
	{
		$this->openPage('/signout');
		//$this->write('verifyTextPresent', 'You have successfully signed out.');	
	}

	/**
	* Sign Up a new user
	*
	* @param string $name
	* @param string $password	
	* @param string $email
	* @return
	* @access public
	*/	
	public function signUp($name, $password, $email)
	{
		$this->openPage('/admin/request_invite');
		$this->write('type', 'email', $email);
		$this->write('type', 'emailconfirm', $email);
		$this->write('clickAndWait', 'signMeUp');	
		$this->write('storeValue', 'key', 'invite_key');
		$this->openPage('/signup/${invite_key}');
		$this->write('type', 'username', $name);
		$this->write('type', 'realname', $name);		
		$this->write('type', 'password', $password);
		$this->write('type', 'passwordconfirm', $password);	
		$this->write('type', 'email', $email);
		$this->click('Sign Up');
		$this->write('verifyTextPresent', 'Your account has been created.');		
		$this->openPage('/admin/delete_invite/${invite_key}');		
	}
	
	/**
	 * Make a group and sign up an existing user as member
	 *
	 * @access public
	 * @param string $group Name of group
	 * @param string $owner_name username of owner
	 * @param string $owner_password password of owner
	 * @param string $name Name of member
	 * @param string $password Password of member
	 * @param string $email Email of member
	 * @return 
	 */
	public function subscribeToGroup($group, $owner_name, $owner_password, $name, $password, $email)
	{
		$key = $this->randomString(10);
		$this->signOut();
		$this->signIn($owner_name, $owner_password);
		$this->openPage('/groups/invites/' . $group);	
		$this->write('type', 'invites', $email);	
		$this->click('Create Invitations');
		$this->write('storeValue', 'count-0', $key);		
		$this->signOut();
		$this->signIn($name, $password);
		$this->openPage('/groups/accept/${' . $key . '}');
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
	 * Make sure the full user profile is present
	 *
	 * @access public
	 * @param string $page
	 * @param string $username	
	 * @return 
	 */
	public function userSbFull($page, $username)
	{
		$this->openPage($page);
		$this->write('verifyElementNotPresent', 'sb_signup');
		$this->write('verifyElementNotPresent', 'sb_signin');
		$this->write('verifyElementNotPresent', 'sb_stats');
		$this->write('verifyElementNotPresent', 'sb_menu_short');
		$this->write('verifyTextPresent', $username . ' is not currently following anyone.');						
		$this->write('verifyElementPresent', 'sb');
		$this->write('verifyElementPresent', 'sb_profile');	
		$this->write('verifyElementPresent', 'sb_following');
		$this->write('verifyElementNotPresent', 'sb_menu_short');			
	}
	
	/**
	 * Make sure the partial user profile is present
	 *
	 * @access public
	 * @param string $page
	 * @param boolean $signedIn Set to false if testing non-signed in page	
	 * @param boolean $showSignUp Set to false if testing for sign up form
	 * @return 
	 */
	public function userSbProfile($page, $showSignIn = false, $showSignUp = false)
	{
		$this->openPage($page);
		if ($showSignIn) 
		{
			$this->write('verifyElementPresent', 'sb_signin');			
		}
		if ($showSignUp) 
		{
			$this->write('verifyElementPresent', 'sb_signup');			
		}
		$this->write('verifyElementPresent', 'sb');		
		$this->write('verifyElementPresent', 'sb_stats');
		$this->write('verifyElementPresent', 'sb_following');
		$this->write('verifyElementPresent', 'sb_menu_short');
		$this->write('verifyElementNotPresent', 'sb_menu_long');
		$this->write('verifyElementNotPresent', 'sb_profile');
	}
	
	/**
	 * Make sure the partial user profile is present
	 *
	 * @access public
	 * @param string $page
	 * @param boolean $signedIn Set to false if testing non-signed in page	
	 * @param boolean $showSignUp Set to false if testing for sign up form
	 * @return 
	 */
	public function userSbNone($page, $showSignIn = false, $showSignUp = false)
	{
		$this->openPage($page);
		if ($showSignIn) 
		{
			$this->write('verifyElementPresent', 'sb_signin');			
		}
		if ($showSignUp) 
		{
			$this->write('verifyElementPresent', 'sb_signup');			
		}
		$this->write('verifyElementNotPresent', 'sb');		
		$this->write('verifyElementNotPresent', 'sb_stats');
		$this->write('verifyElementNotPresent', 'sb_following');
		$this->write('verifyElementNotPresent', 'sb_menu_short');
		$this->write('verifyElementNotPresent', 'sb_menu_long');
		$this->write('verifyElementNotPresent', 'sb_profile');
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