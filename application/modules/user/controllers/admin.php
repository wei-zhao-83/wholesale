<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	public function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$filter = $this->input->post();
		
		$data = array('roles' => $this->em->getRepository('role\models\Role')->findAll(),
					  'users'  => $this->em->getRepository('user\models\User')->getUsers($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');		
	}
	
	public function create() {
		$user = new user\models\User;
		
		$data = array('roles' => $this->em->getRepository('role\models\Role')->findAll(),
					  'user'  => $user);
		
		if ($this->_user_validate() !== FALSE) {
			try {
				$this->_do($user);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'User successfully inserted.'));
				redirect('admin/user/edit/' . $user->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this user.'));
				redirect('admin/user');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/form_create', $data);
		$this->load->view('admin/footer');
	}
	
	public function edit() {
		$id = $this->uri->segment(4);
		$user = $this->em->getRepository('user\models\User')->findOneBy(array('id' => $id));
		
		if (!$user) {
			redirect('admin/user');
		}
		
		$data = array('roles' => $this->em->getRepository('role\models\Role')->findAll(),
					  'user'  => $user);
		
		if ($this->_user_validate() !== FALSE) {
			try {
				$this->_do($user);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'User successfully updated.'));
				redirect('admin/user/edit/' . $user->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/user');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/form_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function delete() {
		$id = $this->uri->segment(4);
		
		try {
			$user = $this->em->getRepository('user\models\User')->findOneBy(array('id' => $id));
			$this->em->remove($user);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/user');
	}

	/**
	 * Handling bulk actions
	 *
	 * @access public
	 * @param string $action  update | delete | do_delete
	 * @param array $_POST
	 * @return void
	 */
	public function batch(){
		$selected_ids = $this->input->post('ids');
		$action = $this->input->post('action');
		
		switch($action) {
			case 'insert':
			break;
			
			case 'update':
			break;
			
			case 'delete':
				if(!empty($selected_ids) && is_array($selected_ids)) {					
					foreach ($selected_ids as $id) {
						$user = $this->em->getRepository('user\models\User')->findOneBy(array('id' => $id));
						$this->em->remove($user);
					}
					$this->em->flush();
					
					$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
				} else {
					$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'error'));
				}
			break;
			
			default:
			break;
		}
		
		redirect('admin/user');
	}
	
	private function _do($user) {
		if ($user instanceof user\models\User) {
			$role = $this->em->getRepository('role\models\Role')->findOneBy(array('id' => $this->input->post('role')));
			$password = $this->input->post('password');
			
			if ($this->input->post('edit')) {
				$current_role = $user->getRole();
				$current_metas = $user->getUserMetas();
				
				$user->removeRole($current_role);
				
				foreach ($current_metas as $current_meta) {
					$user->removeUserMeta($current_meta);
					$this->em->remove($current_meta);
				}
			}
			
			$user->setUsername($this->input->post('username'));
			$user->setPhone($this->input->post('phone'));
			$user->setEmail($this->input->post('email'));
			$user->setActive($this->input->post('active'));
			$user->setRole($role);
			$user->setLastLoginAt(new \DateTime());
			
			if (!$this->input->post('edit') || !empty($password)) {
				$salt = $this->auth->salt(6);
				$hashed_password = $this->auth->hash_password($password, $salt);
				
				$user->setSalt($salt);
				$user->setPassword($hashed_password);
			}
			
			foreach ($this->input->post('user_metas') as $key => $value) {
				$meta = new user\models\UserMeta;
				$meta->setKey($key);
				$meta->setValue($value);
				
				$user->addUserMeta($meta);
			}
			
			$this->em->persist($user);
			$this->em->flush();
		} else {
			throw new Exception('User not exists.');
		}
	}
	
	/**
	 * User validation function
	 *
	 * @access private
	 * @return bool
	 */
	private function _user_validate() {
		$user_validation_rule = array(
			//[0]
			array('field'=>'username',
				  'label'=>'Username',
				  'rules'=>'required|xss_clean|is_unique[user\models\User.username]'),
			//[1]
			array('field'=>'password',
				  'label'=>'Password',
				  'rules'=>'required|min_length[6]'),
			//[2]
			array('field'=>'email',
				  'label'=>'Email',
				  'rules'=>'required|valid_email|is_unique[user\models\User.email]'),
			//[3]
			array('field'=>'phone',
				  'label'=>'Phone',
				  'rules'=>'required|valid_phone_number'),
			//[4]
			array('field'=>'role',
				  'label'=>'Role',
				  'rules'=>'required'),
			//[5]
			array('field'=>'active',
				  'label'=>'Active',
				  'rules'=>'required'),
			//[6]
			array('field'=>'user_metas[comment]',
				  'label'=>'Comment',
				  'rules'=>''),
			//[7]
			array('field'=>'user_metas[firstname]',
				  'label'=>'Firstname',
				  'rules'=>''),
			//[8]
			array('field'=>'user_metas[lastname]',
				  'label'=>'Lastname',
				  'rules'=>'')
		);
		
		if($this->input->post('edit')) {
			$user = $this->em->getRepository('user\models\User')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('username') == $user->getUsername()) {
				unset($user_validation_rule[0]);
			}
			
			if($this->input->post('email') == $user->getEmail()) {
				unset($user_validation_rule[2]);
			}
			
			if ($this->input->post('password') == '') {
				unset($user_validation_rule[1]);
			}
		}
		
		$this->form_validation->set_rules($user_validation_rule); 
		return $this->form_validation->run();
	}
}