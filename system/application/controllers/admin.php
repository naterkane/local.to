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
 * @filesource
 */
/**
 * Database admin functions
 * Used only for testing. Admin will be available if config['display_errors'] is set to true
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
	 * Check to see if in testing mode, if not, show 404
	 * 
	 * @access public
	 * @return 
	 */
	public function __construct()
	{
		parent::__construct();
		if (!$this->testing()) 
		{
			$this->show404();
		}
	}

	/**
	 * Delete cookie, used for cookie tests
	 *
	 * @access public
	 * @param string 'user_agent' or 'ip' -- all other values ignored
	 * @return 
	 */
	public function cookie($type = null)
	{
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
	 * Flushes the database
	 * @return 
	 * @todo make private method and require authentication
	 */
	function flush() 
	{
		$this->User->tt->vanish();
		$this->redirect('/admin/stats');
	}

	/**
	 * Show all database values 
	 * @return 
	 * @todo make private method and require authentication
	 */	
	function stats()
	{
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
		echo "<tr><th>Count (" . count($all) . ")</th><th>Key</th><th>Value</th></tr>\n";
		$i = 1;
		foreach ($all as $key => $value) {
			echo "<tr><td>$i</td>";
			echo "<td>$key</td>";
			echo "<td><pre>";
			print_r($value);
			echo "<pre></td></tr>\n";
			$i++;
		}
		echo "<tr><td>&nbsp;</td><td>Stats</td><td>";
		echo "<pre>";
		print_r($this->User->tt->stat());
		echo "</pre>";
		echo "</td></tr>";
		echo "</table>";
		exit;
	}

	/**
	 * loads the memcache control panel
	 * @return 
	 * @todo rewrite views/admin/memcache.php and make it work with TT/TC
	 */
	function memcache()
	{
		$this->load->view('/admin/memcache');
	}

	/**
	 * dumps the TT database
	 * 
	 * path to database file needs to exist on the same server on which the App is running
	 * 
	 * @todo make the path to the .tch file dynamic or configurable
	 * @return 
	 */
    function showdata($prefix = null)
    {
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
	 * @return 
	 */
	function request_invite()
	{
		$this->layout = "public";
		$this->load->model("message");
		$this->load->view('admin/request_invite', $this->data);	
	}

	/**
	 * Create an invite. Only used for testing.
	 * 
	 * @return 
	 * @todo add a flag to $config to turn on or off automatic email generation.
	 */
	function create_invite()
	{
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
	 * @return 
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
?>