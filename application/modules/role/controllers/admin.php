<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	function  __construct() {
		parent::__construct();		
	}
	
	public function index() {		
		$data = array('roles' => $this->em->getRepository('role\models\Role')->findAll());
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');		
	}
	
	public function create() {
		$role = new role\models\Role;
		
		$data = array('role' => $role,
					  'permission' => $this->em->getRepository('permission\models\Permission')->getPermissionsGroupByModule(),
					  'selected_perm_ids' => ($this->input->post('perm_ids'))?$this->input->post('perm_ids'):array());
		
		if ($this->_role_validate() !== FALSE) {
			try {
				$this->_do($role);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Role successfully inserted.'));
				redirect('admin/role/edit/' . $role->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this role.'));
				redirect('admin/role');
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
		$role = $this->em->getRepository('role\models\Role')->findOneBy(array('id' => $id));
		
		if (!$role) {
			redirect('admin/role');
		}
		
		$current_perm_ids = array();
		foreach ($role->getPermissions() as $perm) {
			$current_perm_ids[] = $perm->getId();
		}
		
		$data = array('role' => $role,
					  'permission' => $this->em->getRepository('permission\models\Permission')->getPermissionsGroupByModule(),
					  'selected_perm_ids' => ($this->input->post('perm_ids'))?$this->input->post('perm_ids'):$current_perm_ids);
		
		if ($this->_role_validate() !== FALSE) {
			try {
				$this->_do($role);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Role successfully updated.'));
				redirect('admin/role/edit/' . $role->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/role');
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
			$role = $this->em->getRepository('role\models\Role')->findOneBy(array('id' => $id));
			$num_of_users = $this->em->getRepository('user\models\User')->countUsersBy(array('role' => $role->getId()));
			
			if ($num_of_users > 0) {
				throw new Exception('You must delete all users associated with this role before deleting the role.');
			} elseif ($role->getRemovable() == 0) {
				throw new Exception('This role is not removable');
			} else {
				$this->em->remove($role);
				$this->em->flush();
			}
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/role');
	}
	
	private function _do(role\models\role $role) {
		if ($role instanceof role\models\Role) {
			if ($this->input->post('edit')) {
				$current_permissions = $role->getPermissions();
				
				foreach ($current_permissions as $current_permission) {
					$role->removePermission($current_permission);
				}
			}
			
			$role->setName($this->input->post('name'));
			$role->setRemovable($this->input->post('removable'));
			$role->setDescription($this->input->post('description'));
			
			foreach ($this->input->post('perm_ids') as $perm_id) {
				$perm = $this->em->getRepository('permission\models\Permission')->findOneBy(array('id' => $perm_id));
				$role->addPermission($perm);
			}
			
			$this->em->persist($role);
			$this->em->flush();
		} else {
			throw new Exception('Role not exists.');
		}
	}
	
	private function _role_validate() {
		$role_validation_rule = array(
			//[0]
			array('field'=>'name',
				  'label'=>'name',
				  'rules'=>'required|xss_clean|is_unique[role\models\Role.name]'),
			//[1]
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>'max_length[250]'),
			//[2]
			array('field'=>'removable',
				  'label'=>'Removable',
				  'rules'=>'required')
		);
		
		if($this->input->post('edit')) {
			$role = $this->em->getRepository('role\models\Role')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('name') == $role->getName()) {
				unset($role_validation_rule[0]);
			}
		}
		
		$this->form_validation->set_rules($role_validation_rule); 
		return $this->form_validation->run();
	}
}