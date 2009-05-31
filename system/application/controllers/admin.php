<?php
/**
* Db admin functions
*/
class Admin extends App_controller
{

	/**
	 * Delete cookie, used for cookie tests
	 *
	 * @access public
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
		//$this->load->helper('Util');
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
					$message = "Welcome to ". $this->config->item("service_name") ."!\n\nPlease kindly accept our invitation to join ". $this->config->item("site_name") ." by following this link\n". $this->config->item("base_url")."signup/".base64_encode($data['email'])."/".$data['key'];
					$this->mail->send($data['email'], null, null, 'Welcome to '.$this->config->item('service_name'), $message);
					ob_end_clean();	
					$this->redirect("/signup/".$this->util->base64_url_encode($data['email'])."/".$data['key'],"We've got your info, please click the link in the email we've sent to <strong>".$data['email']."</strong>.");
				
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
				$this->data['key'] = $this->util->base64_url_encode(preg_replace('/@/',$this->Invite->randomString(9),$this->data['email']));
				$this->Invite->create($this->data);		
				$this->data['email'] = $this->util->base64_url_encode($this->data['email']);
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
		$this->load->library(array('Mail'));
		echo $this->mail->sms('', $user['email'], $data['message']);
		exit;
	}

}
?>