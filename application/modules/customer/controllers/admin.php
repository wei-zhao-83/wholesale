<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {	
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$filter = $this->input->post();
		if ($this->input->post('as_values_tags')) {
			$filter['tags'] = explode(',', $this->input->post('as_values_tags'));
		}
		
		$data = array('customers' => $this->em->getRepository('customer\models\Customer')->getCustomers($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$customer = new customer\models\Customer;		
		
		$data = array('customer' => $customer);
		
		if ($this->_customer_validate() !== FALSE) {	
			try {
				$this->_do($customer);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Customer successfully inserted.'));
				redirect('admin/customer/edit/' . $customer->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this customer.'));
				redirect('admin/customer');
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
		$customer = $this->em->getRepository('customer\models\Customer')->findOneBy(array('id' => $id));
		
		if (!$customer) {
			redirect('admin/customer');
		}
		
		$current_tags = array();
		foreach ($customer->getTags() as $tag) {
			$current_tags[] = $tag->getName();
		}
		
		$data = array('customer' => $customer,
					  'current_tags' => implode(',', $current_tags));
		
		if ($this->_customer_validate() !== FALSE) {
			try {
				$this->_do($customer);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Customer successfully updated.'));
				redirect('admin/customer/edit/' . $customer->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/customer');
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
			$customer = $this->em->getRepository('customer\models\Customer')->findOneBy(array('id' => $id));
			
			// Soft Delete
			$customer->setDeletedAt(new DateTime);
			$this->em->persist($customer);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/customer');
	}
	
	private function _do(customer\models\Customer $customer) {
		if ($this->input->post('edit')) {
			// remove the current tags
			$current_tags = $customer->getTags();
			foreach ($current_tags as $current_tag) {
				$customer->removeTag($current_tag);
			}
		}
		
		// add selected tags
		$tags = explode(',', $this->input->post('as_values_tags'));
		foreach($tags as $tag_name) {
			$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('name' => $tag_name));
			if ($tag) {
				$customer->addTag($tag);
			}
		}
		
		// Reflection object
		$reflected_model = new ReflectionClass('customer\models\Customer');
		
		foreach($this->input->post() as $field => $value) {
			$_method = 'set' . implode(array_map('ucfirst', explode('_', $field)));
			
			if ($reflected_model->hasMethod($_method)) {
				$customer->$_method($value);
			}
		}			
		
		$this->em->persist($customer);
		$this->em->flush();
	}
	
	private function _customer_validate() {
		$_rules = $this->config->item('rule_customer', 'validate');
		
		if($this->input->post('edit')) {
			$customer = $this->em->getRepository('customer\models\Customer')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('email') == $customer->getEmail()) {
				unset($_rules['email']);
			}
		}
		
		$this->form_validation->set_rules($_rules); 
		return $this->form_validation->run();
	}
}