<?php
/**
 * Class for users controller
 */
class Users extends App_Controller
{
   
    /**
     * Follow a user
     *
     * @todo check if user is not yourself
     * @param string $username
     */
    function follow($username = null)
    {
        $this->mustBeSignedIn();
		if ($this->User->follow($username, $this->userData['id']))
		{
			$this->redirect('/' . $username);
		}
		else
		{
			show_404();
		}
    }
   
	/**
	 * User's home page
	 * @return 
	 */
    function home()
    {
        $this->mustBeSignedIn();
        $this->data['title'] = 'Home';
        $this->data['messages'] = $this->Message->getPrivate($this->userData['id']);
        $this->load->view('users/home', $this->data);
    }
   
    /**
     * Sign in a user
     * 
     * @return
     * @todo Move those load method calls to one place
     */
    function signin()
    {
        $this->data['title'] = 'Sign In';
        if ($this->postData)
        {
            $user = $this->User->signIn($this->postData);
            if (empty($user))
            {
				$this->cookie->setFlash('The username and password do not match any in our records.', 'error');
            }
			else 
			{
				$this->cookie->set('user', $user);
                $this->redirect('/home');
			}
        }
        $this->load->view('users/signin', $this->data);
    }

    /**
     * Sign up a new user
     * 
     * @return
     * @todo Move those load method calls to one place
     */
    function signup()
    {
        $this->data['title'] = 'Sign Up';
        if ($this->postData)
        {
            if ($this->User->signUp($this->postData))
            {
                $this->redirect('/signin', 'Your account has been created. Please sign in.');
            }
			else 
			{
				$this->setErrors(array('User'));
                $this->cookie->setFlash('There was an error signing up. Please see below for details.', 'error');
			}
        }
        $this->load->view('users/signup', $this->data);
    }
   
    /**
     * Sign out a user
     * 
     * @return
     */
    function signout()
    {
        $this->cookie->remove('user');
        $this->redirect('/signin', 'You have successfully signed out.');
    }
 
    /**
     * View a users public page
     *
     * @param string $username
     * @return
     */
    function view($username)
    {	
       	$user = $this->User->getByUsername($username);
        if ($user)
        {
            $this->getUserData();
            $this->data['title'] = 'Home';
            $this->data['username'] = $username;
            $this->data['messages'] = $this->Message->getPublic($user['id']);
            $this->data['is_following'] = $this->User->isFollowing($user['id'], $this->userData['id']);
            $this->load->view('users/view', $this->data);
        }
        else
        {
            show_404();
        }
    }
   
}

?>