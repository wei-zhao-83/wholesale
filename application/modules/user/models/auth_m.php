<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_m extends MY_Model {
	
	protected $user = false;
	
    public function __construct() {
		parent::__construct();
	}
    
    public function login($identity, $password) {
        if (empty($identity) || empty($password)) {
			return false;
		}
        
		try {
			$user = $this->em->getRepository('user\models\User')->getUserByIdentity($identity);
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'User does not exist.'));
			return false;
		}
		
		// check user active
		if ($user->getActive() != 1) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'User not activated.'));
			return false;
		}
		
		// check password
		$password = self::hash_password($password, $user->getSalt());
		
        if ($user->getPassword() ===  $password) {
			$this->_set_login($user);
			return true;
		} else {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'The username/password combination is incorrect.'));
			return false;
		}
		
		return false;
    }
    
    public function logout() {
	    $this->session->unset_userdata('username');
		$this->session->unset_userdata('email');
	    $this->session->unset_userdata('role_id');
	    $this->session->unset_userdata('id');
		
		$this->session->sess_destroy();
		
		return true;
    }
    
	public function is_logged_in() {
		$logged_in = $this->session->userdata('logged_in');
		
		if ($logged_in) {
			return $logged_in;
		}
		
		return false;
	}
	
	public function hash_password($password, $salt) {
		if (empty($password)) {
			return false;
	    }
		
	    if (!empty($salt)) {
			return  sha1($password . $salt);
		}
		
		return false;
	}
	
	public function salt($length) {
		return substr(md5(uniqid(rand(), true)), 0, $length);
	}
	
	public function current_user() {
		if ($this->user) {
			return $this->user;
		}
		
		$id = $this->session->userdata('id');
		
		try {
			$user = $this->em->getRepository('user\models\User')->findOneById($id);
			// Save for later use
			$this->user = $user;
			
			return $user;
		} catch (Exception $e) {
			return false;
		}
	}
	
    private function _set_login($user) {
        $user->setLastLoginAt(new \DateTime());
		
		$this->session->set_userdata(array(
			'username' 	=> $user->getUsername(),
			'email' 	=> $user->getEmail(),
			'id'        => $user->getId(),
			'role_id'   => $user->getRole()->getId(),
			'logged_in'	=> true
		));
		
		$this->em->persist($user);
		$this->em->flush();
    }
}

?>