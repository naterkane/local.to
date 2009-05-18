<?php
/**
* Db admin functions
*/
class Admin extends App_controller
{
	
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
	
	function stats()
	{
		
		foreach($this->User->tt->fwmkeys('', 1000) as $key)
		{
			$all[$key] = $this->User->tt->get($key);
			if ($all[$key]!="")
				unserialize($all[$key]);
			
			if (is_array($all[$key]))
				ksort($all[$key]);
		}	
		ksort($all);
		$stats = $this->User->tt->stat();
		echo "<a href=\"/admin/flush\">Flush again</a> <a href=\"/admin/flush\">Go to Tests</a> <a href=\"/admin/showdata\">Reloads</a><br>";		
		echo "<pre>";
		print_r($all);
		print_r($stats);
		echo "</pre>";
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
	 * Loads the form to request an invitation code
	 * @return 
	 */
	function request_invite()
	{
		$this->layout = "public";
		$this->load->view('admin/request_invite');	
	}

	/**
	 * 
	 * 
	 * @return 
	 * @todo add a flag to $config to turn on or off automatic email generation.
	 */
	function create_invite()
	{
		
		if ($this->postData){
			
			$this->load->model(array('Invite'));
			$this->load->database();
			if ($this->postData['email'] == $this->postData['emailconfirm'])
			{
				$data['email'] = $this->postData['email'];
				$data['key'] = base64_encode(preg_replace('/@/',$this->Invite->randomString(9),$data['email']));
				try{
					$this->Invite->create($data);
					$this->load->library('Mail');
					$random_hash = md5(date('r', time()));
					//define the headers we want passed. Note that they are separated with \r\n
					$headers = "From: webmaster@example.com\r\nReply-To: webmaster@example.com";
					//add boundary string and mime type specification
					$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\""; 
					ob_start(); // since debugging is set to '2', let's make sure we don't send anything to the browser until we set headers for the actual view.
					$message = "Welcome to ". $this->config->item("base_url") ."!\n\nPlease kindly accept our invitation to ". $this->config->item("base_url") ." by following this link\n". $this->config->item("base_url")."signup/".base64_encode($data['email'])."/".$data['key'];
					$this->mail->send($data['email'], null, null, 'Welcome to '.$this->config->item('base_url'), $message);
					ob_end_clean();	
					$this->redirect("/signup/".base64_encode($data['email'])."/".$data['key'],"We've got your info, please click the link in the email we've sent to <strong>".$data['email']."</strong>.");
				
				}		
				catch(Exception $e)
				{
					$this->redirect('/request_invite', 'Caught exception: ',  $e->getMessage(), "\n");
				}
			}
			else
			{
				$this->redirect("/request_invite","There's something wrong with the email you entered, please give it another go.");	
			}
		} 
		else 
		{
			/**
			 * for running via a test, generates a generic invites
			 * @todo require some sort of authentication so we can keep this
			 */
			$testing = $this->testing();
			if ($testing > 0) 
			{
				$this->layout = 'bare';
				$this->load->model(array('Invite'));
				$this->load->database();				
				$this->data['email'] = "nomcat+" . $this->Invite->randomString(10) . '@wearenom.com';
				$this->data['key'] = base64_encode(preg_replace('/@/',$this->Invite->randomString(9),$data['email']));
				$this->Invite->create($this->data);		
				$this->data['email'] = base64_encode($this->data['email']);
				$this->load->view('admin/create_invite', $this->data);
			}
		}
	}
	
	function delete_invite($email, $key)
	{
		$testing = $this->testing();
		if ($testing > 0) 
		{
			$this->layout = 'bare';
			$this->load->model(array('Invite'));
			$this->load->database();
			$data = array();				
			$email = base64_decode($email);			
			$this->Invite->delete($email, $key);		
			$this->redirect('/home', 'Key has been deleted');
		}
	}

	function test() 
	{
		$user = $this->user->find();
		$user = $this->tt->get();
		
	}

}
?>