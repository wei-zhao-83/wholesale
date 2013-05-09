<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	function  __construct() {
		parent::__construct();		
	}
	
	public function index() {
		$filter = $this->input->post();
		
		$data = array('permissions' => $this->em->getRepository('permission\models\Permission')->getPermissions($filter),
					  'modules' => $this->em->getRepository('module\models\Module')->findAll());
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');	
	}
	
	public function create() {
		$permission = new permission\models\Permission;
		
		$data = array('modules' => $this->em->getRepository('module\models\Module')->findAll(),
					  'permission'  => $permission);
		
		if ($this->_permission_validate() !== FALSE) {
			try {
				$this->_do($permission);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Permission successfully inserted.'));
				redirect('admin/permission/edit/' . $permission->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this permission.'));
				redirect('admin/permission');
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
		$permission = $this->em->getRepository('permission\models\Permission')->findOneBy(array('id' => $id));
		
		if (!$permission) {
			redirect('admin/permission');
		}
		
		$data = array('modules' => $this->em->getRepository('module\models\Module')->findAll(),
					  'permission'  => $permission);
		
		if ($this->_permission_validate() !== FALSE) {
			try {
				$this->_do($permission);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Permission successfully updated.'));
				redirect('admin/permission/edit/' . $permission->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/permission');
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
			$permission = $this->em->getRepository('permission\models\Permission')->findOneBy(array('id' => $id));
			
			if ($permission->getRemovable() == 0) {
				throw new Exception('This permission is not removable');
			} else {
				$this->em->remove($permission);
				$this->em->flush();
			}
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/permission');
	}
	
	private function _do(permission\models\Permission $permission) {
		if ($permission instanceof permission\models\Permission) {            
            if ($this->input->post('edit')) {
				//$permission->removeRole($current_role);
			}
            
			$permission->setName($this->input->post('name'));
			$permission->setRemovable($this->input->post('removable'));
            $permission->setDescription($this->input->post('description'));
            $permission->setModule($this->input->post('module'));
            
			$this->em->persist($permission);
			$this->em->flush();
		} else {
			throw new Exception('Permission not exists.');
		}
	}
	
	private function _permission_validate() {
		$permission_validation_rule = array(
			//[0]
			array('field'=>'name',
				  'label'=>'Name',
				  'rules'=>'required|xss_clean|min_length[4]'),
			//[1]
			array('field'=>'module',
				  'label'=>'Module',
				  'rules'=>'required|xss_clean'),
			//[2]
			array('field'=>'removable',
				  'label'=>'Removable',
				  'rules'=>'required'),
            //[3]
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>'max_length[250]')
		);
		
		$this->form_validation->set_rules($permission_validation_rule); 
		return $this->form_validation->run();
	}
}