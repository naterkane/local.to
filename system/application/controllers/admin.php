<?php
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
 * @filesource	/system/application/controllers/admin.php
 */
/**
 * Database admin functions
 * Used only for testing. Admin will be available if <code>$config['display_errors']</code> or <code>$config['testing']</code> is set to true
 *
 * @package 	Nomcat
 * @subpackage	nomcat-controllers
 * @category	controller
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 * @todo 		Remove these functions and place in a separate DB admin app.
 */
class Admin extends App_controller
{

	/**
	 * Check to see if in testing mode, if not, redirect to root
	 * 
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
		//if (!$this->testing()) 
		//{
		//	$this->redirect('/');
		//}
		//$this->redirect('/admin/login');
	}

	

	/**
	 * Show phpinfo content
	 */
	function _info(){
		//$this->mustBeSignedIn();
		if ($this->config->item("testing") != true) {
			return false;
		}
		phpinfo();
		exit();
	}

	/**
	 * Delete cookie, used for cookie tests
	 *
	 * @access public
	 * @param string 'user_agent' or 'ip' -- all other values ignored
	 */
	public function cookie($type = null)
	{
		$this->mustBeSignedIn();
		if ($type == 'user_agent') 
		{
			$cookie = $this->cookie->getAllData();
			$cookie['user_agent'] = 'x';
			$this->Cookie_model->save($cookie);
		}
		if ($type == 'ip') 
		{
			$cookie = $this->cookie->getAllData();
			$cookie['user_agent'] = 'ip';
			$this->Cookie_model->save($cookie);
		}		
		exit;
	}
		
	/**
	 * Deletes a key
	 * 
	 * @param object $key [optional]
	 */
	function delete($key = null) {
		$this->mustBeSignedIn();
		if (null == $key)
			$this->redirect('/admin/stats');
		
		try
		{
			// decode base64 encoded key
			$key = base64_decode($key);
			$this->User->tt->out($key);
		}
		catch (Exception $e)
		{
			$this->redirect('/admin/stats',"Sorry but we had trouble deleting the record: '.$key.'<br/>".$e,"error");
		}		
	$this->redirect('/admin/stats','Successfully deleted: '.$key,"success");
	}	
	
	/**
	 * Flushes the database
	 * @todo make private method and require authentication
	 */
	function flush() 
	{
		$this->mustBeSignedIn();
		if ($this->config->item("testing") != true) {
			$this->redirect('/admin/stats','no way mister!','error');
		}
		$this->User->tt->vanish();
		$this->redirect('/admin/stats');
	}

	function mustBeSignedIn() {
		$this->login();
	}

	/**
	 * 
	 * @return void
	 */
	function login()
	{
		$this->redirect('/');
	}

	/**
	 * 
	 * @return void
	 */
	function logout()
	{
		
	}

	/**
	 * Loads the memcache control panel
	 * 
	 * @todo rewrite views/admin/memcache.php and make it work with TT/TC
	 */
	function memcache()
	{
		$this->mustBeSignedIn();
		$this->load->view('/admin/memcache');
	}

	/**
	 * Syncs in-memory data to disk
	 */
	function sync()
	{
		$this->mustBeSignedIn();
		$this->User->tt->sync();
		$this->redirect('/admin/stats');
	}


	/**
	 * Show all database values 
	 * 
	 * @todo make private method and require authentication
	 */	
	function stats()
	{
		//$this->mustBeSignedIn();
		$all = array();
		foreach($this->User->tt->fwmkeys('', 1000) as $key)
		{
			$all[$key] = $this->User->tt->get($key);
			if ($this->User->isSerialized($all[$key]))
				$all[$key] = unserialize($all[$key]);
			
			if (is_array($all[$key]))
			{
				ksort($all[$key]);				
			}
		}	
		ksort($all);
		echo "<a href=\"/admin/flush\">Flush again</a> <a href=\"/admin/flush\">Go to Tests</a> <a href=\"/admin/showdata\">Reloads</a><br>";		
		echo "<table border=\"1\" cellspacing=\"5\" cellpadding=\"5\">\n";
		echo "<tr><th></th><th>Count (" . count($all) . ")</th><th>Key</th><th>Value</th></tr>\n";
		$i = 1;
		foreach ($all as $key => $value) {
			echo "<tr><td><form action='delete/".base64_encode($key)."' method='post'><button>delete</button></form></td><td>$i</td>";
			echo "<td valign='top'><code>$key</code></td>";
			echo "<td><pre>";
			print_r($value);
			echo "<pre></td></tr>\n";
			$i++;
		}
		echo "</table>";
		echo "<h3>Stats</h3>";
		echo "<pre>";
		print_r($this->User->tt->stat());
		echo "</pre>";
		$this->_info();
		exit;
	}

	/**
	 * dumps the TT database
	 * 
	 * path to database file needs to exist on the same server on which the App is running
	 * 
	 * @todo make the path to the .tch file dynamic or configurable
	 */
    function showdata($prefix = null)
    {
	    	$this->mustBeSignedIn();
        echo "<a href=\"/admin/flush\">Flush</a> <a href=\"/admin/showdata\">Reloads</a><br>";
        echo '<p><code>@todo</code> append a <code>prefix</code> to the url of this page to filter results</p>';
        echo "<pre>";
        if (!empty($prefix))
        { 
			print_r(shell_exec('/usr/local/bin/tchmgr list -nl -pv -fm "'.$prefix.'" /data/mydb.tdh'));
 		}
        else
        {
	        print_r(shell_exec("/usr/local/bin/tchmgr list -nl -pv /data/mydb.tch"));
	        //var_dump(exec("/usr/local/bin/tchmgr list -nl -pv /data/mydb.tch"));
        }   
        echo "</pre>";
        exit;

	}

	/**
	 * Loads the form to request an invitation code, only used for testing
	 */
	function request_invite()
	{
		if ($this->config->item('registration_open') == true) 
		{
			$this->layout = "public";
			$this->load->model("message");
			$this->load->view('admin/request_invite', $this->data);	
		}
		else 
		{
			$this->redirect("/");
		}
	}

	/**
	 * Create an invite. Only used for testing.
	 * 
	 * @todo add a flag to $config to turn on or off automatic email generation.
	 */
	function create_invite()
	{
		//echo "poop"; exit;
		if ($this->config->item('registration_open') == false) {
			$this->redirect('/', "Sorry, you can't create an account this way",'error');
		}
		//$this->load->helper('Util');
		if ($this->postData){
			
			$this->load->model(array('Invite'));
			$this->load->database();
			if ($this->postData['email'] == $this->postData['emailconfirm'])
			{
				$data['email'] = $this->postData['email'];
				$data['key'] = $this->Invite->randomString(10);
				$data['key_hashed'] = md5($data['key']);	
				$data['created'] = date('Y-m-d H:i:s');
				$data['modified'] = $data['created'];
				$data['permission'] = 'member';				
					if ($this->Invite->create($data)) 
					{
						$random_hash = md5(date('r', time()));
						$path = 'signup/' . $data['key'];
						$url = $this->config->item("base_url") . $path;
						$this->mail->sendInvite($data['email'], $url);
						if (!$this->config->item('testing')) 
						{
							$path = null;
						}
						$this->redirect('/' . $path, "We've got your info, please click the link in the email we've sent to <strong>" . $data['email'] . "</strong>.");							
					} 
					else 
					{
						$this->redirect("/request_invite","There's something wrong with the email you entered, please give it another go.");	
					}
			}
			else
			{
				$this->redirect("/request_invite","There's something wrong with the email you entered, please give it another go.");	
			}
		} 
		else 
		{
			if ($this->config->item('testing')) 
			{
				$this->layout = 'bare';
				$this->load->model(array('Invite'));
				$this->load->database();				
				$this->data['email'] = "nomcat+" . $this->Invite->randomString(10) . '@wearenom.com';
				$this->data['key'] = $this->util->base64_url_encode(preg_replace('/@/',$this->Invite->randomString(9),$this->data['email']));
				$this->Invite->create($this->data);		
				$this->data['email'] = $this->util->base64_url_encode($this->data['email']);
				$this->load->view('admin/create_invite', $this->data);
			}
			else 
			{
				$this->show404();
			}
		}
	}
	
	/**
	 * Deletes an invite, only used for testing
	 *
	 * @param string $key to be deleted
	 */	
	function delete_invite($key)
	{
		$this->layout = 'bare';
		$this->load->model(array('Invite'));
		$this->load->database();
		$data = array();						
		$this->Invite->delete($key);		
		$this->redirect('/home', 'Key has been deleted');
	}

}