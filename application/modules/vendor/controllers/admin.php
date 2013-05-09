<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	//private $upload_path = '';
	
	function  __construct() {
		parent::__construct();
		
		//$config['upload_path'] = $this->upload_path = $this->config->item('dealer_image_upload_path', 'config_site');
		//$config['allowed_types'] = $this->config->item('image_allowed_types', 'config_site');
		//$config['max_size']		 = $this->config->item('image_max_size', 'config_site');
		//$config['max_width'] 	 = $this->config->item('image_max_width', 'config_site');
		//$config['max_height'] 	 = $this->config->item('image_max_height', 'config_site');		
		//
		//$this->load->library('upload', $config);
	}
	
	public function index() {
		$filter = $this->input->post();		
		
		if ($this->input->post('as_values_tags')) {
			$filter['tags'] = explode(',', $this->input->post('as_values_tags'));			
		}
		
		$data = array('vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$vendor = new vendor\models\Vendor;		
		
		$data = array('vendor' => $vendor);
		
		if ($this->_vendor_validate() !== FALSE) {	
			try {
				$this->_do($vendor);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Vendor successfully inserted.'));
				redirect('admin/vendor/edit/' . $vendor->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/vendor');
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
		$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneBy(array('id' => $id));
		
		if (!$vendor) {
			redirect('admin/vendor');
		}		
		
		$current_tags = array();
		foreach ($vendor->getTags() as $tag) {
			$current_tags[] = $tag->getName();
		}		
		
		$data = array('vendor' => $vendor,
					  'current_tags' => implode(',', $current_tags));		
		
		if ($this->_vendor_validate() !== FALSE) {
			try {
				$this->_do($vendor);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Vendor successfully updated.'));
				redirect('admin/vendor/edit/' . $vendor->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/vendor');
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
			$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneBy(array('id' => $id));
			
			// Soft Delete
			$vendor->setDeletedAt(new DateTime);
			$this->em->persist($vendor);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/vendor');
	}
	
	private function _do(vendor\models\Vendor $vendor) {
		if ($this->input->post('edit')) {
			// remove the current tags
			$current_tags = $vendor->getTags();
			foreach ($current_tags as $current_tag) {
				$vendor->removeTag($current_tag);
			}
			
			// remove the current contacts
			$current_contacts = $vendor->getContacts();
			foreach ($current_contacts as $current_contact) {
				$vendor->removeContact($current_contact);
				$this->em->remove($current_contact);
			}
		}
		
		// Upload images
		//$reordered_files = reorder_multiple_upload($_FILES['vendor_images']);			
		//
		//if (!empty($reordered_files)) {
		//	foreach ($reordered_files as $key => $file) {
		//		$_FILES['vendor_images'] = $file;
		//		
		//		if ($this->upload->do_upload('vendor_images')) {
		//			$_file_data = $this->upload->data();						
		//			$_image_info = $this->input->post('vendor_images');
		//			
		//			// new image
		//			$image = new image\models\Image;
		//			
		//			$image->setName($_file_data['raw_name']);
		//			$image->setPath($this->upload_path . $_file_data['file_name']);
		//			$image->setAlt($_image_info[$key]['alt']);
		//			$image->setArrange($_image_info[$key]['arrange']);
		//			$image->setMain($_image_info[$key]['main']);
		//			
		//			$vendor->addImage($image);
		//		}
		//	}
		//}
		
		// Add selected tags
		$tags = explode(',', $this->input->post('as_values_tags'));
		foreach($tags as $tag_name) {
			$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('name' => $tag_name));
			if ($tag) {
				$vendor->addTag($tag);
			}
		}
		
		// Add contacts
		foreach ($this->input->post('vendor_contacts') as $key => $value) {
			if (!empty($value['name'])) {
				$contact = new dealer\models\DealerContact;
				$contact->setName($value['name']);
				$contact->setPhone($value['phone']);
				$contact->setDirectLine($value['direct_line']);
				$contact->setComment($value['comment']);
				
				$vendor->addContact($contact);
			}
		}
		
		// Reflection object
		$reflected_model = new ReflectionClass('vendor\models\Vendor');
		
		foreach($this->input->post() as $field => $value) {
			$_method = 'set' . implode(array_map('ucfirst', explode('_', $field)));
			
			if ($reflected_model->hasMethod($_method)) {
				$vendor->$_method($value);
			}
		}			
		
		$this->em->persist($vendor);
		$this->em->flush();
	}
	
	private function _vendor_validate() {
		$_rules = $this->config->item('rule_vendor', 'validate');
		
		if($this->input->post('edit')) {
			$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('email') == $vendor->getEmail()) {
				unset($_rules['email']);
			}
		}
		
		$this->form_validation->set_rules($_rules); 
		return $this->form_validation->run();
	}
}