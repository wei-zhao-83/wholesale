<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	private $upload_path = '';
	
	function  __construct() {
		parent::__construct();
		
		$config['upload_path'] = $this->upload_path = $this->config->item('dealer_image_upload_path', 'config_site');
		$config['allowed_types'] = $this->config->item('dealer_image_allowed_types', 'config_site');
		$config['max_size']	= $this->config->item('dealer_image_max_size', 'config_site');
		$config['max_width']  = $this->config->item('customer_image_max_width', 'config_site');
		$config['max_height']  = $this->config->item('customer_image_max_height', 'config_site');
		
		$this->load->library('upload', $config);
	}
	
	public function index() {
		$filter = $this->input->post();
		if ($this->input->post('as_values_tags')) {
			$filter['tags'] = explode(',', $this->input->post('as_values_tags'));
		}
		
		$data = array('customers' => $this->em->getRepository('customer\models\Customer')->getCustomers($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$customer = new customer\models\Customer;
		
		$post_tags = ($this->input->post('as_values_tags'))?trim($this->input->post('as_values_tags')):'';
		
		$data = array('customer' => $customer,
					  'post_tags' => $post_tags);
		
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
		$this->load->view('admin/menu');
		$this->load->view('admin/form_create', $data);
		$this->load->view('admin/footer');
	}
	
	public function edit() {
		$id = $this->uri->segment(4);
		$customer = $this->em->getRepository('customer\models\Customer')->findOneBy(array('id' => $id));
		
		if (!$customer) {
			redirect('admin/customer');
		}
		
		$tags = $this->em->getRepository('tag\models\Tag')->findAll();
		
		$current_tags = array();
		foreach ($customer->getTags() as $tag) {
			$current_tags[] = $tag->getName();
		}
		
		$data = array('customer' => $customer,
					  'tags' => $tags,
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
		$this->load->view('admin/menu');
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
	
	private function _do($customer) {
		if ($customer instanceof customer\models\Customer) {
            if ($this->input->post('edit')) {
				// remove the current tags
				$current_tags = $customer->getTags();
				foreach ($current_tags as $current_tag) {
					$customer->removeTag($current_tag);
				}
				
				// remove the current image
				$current_images = $customer->getImages();
				foreach ($current_images as $current_image) {
					$customer->removeImage($current_image);
					$this->em->remove($current_image);
				}
				
				// update the current images
				if ($this->input->post('current_customer_images')) {
					foreach ($this->input->post('current_customer_images') as $id => $value) {
						$image = new image\models\Image;
						$image->setName($value['name']);
						$image->setPath($value['path']);
						$image->setAlt($value['alt']);
						$image->setArrange($value['arrange']);
						$image->setMain($value['main']);
						
						$customer->addImage($image);
					}
				}
			}
			
			// upload images first to folder
			foreach($_FILES as $key => $value):
				if ($this->upload->do_upload($key)):
					$array_key = (int)str_replace('image_file_', '', $key);
					$customer_image_array[$array_key] = $this->upload->data();
				endif;
			endforeach;
			
			// add images
			foreach ($this->input->post('customer_images') as $key => $value) {
				if(isset($customer_image_array[$key]['file_name'])) {
					$image = new image\models\Image;
					$image->setName($value['name']);
					$image->setPath($this->upload_path . $customer_image_array[$key]['file_name']);
					$image->setAlt($value['alt']);
					$image->setArrange($value['arrange']);
					$image->setMain($value['main']);
					
					$customer->addImage($image);
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
			
			$customer->setName($this->input->post('name'));
			$customer->setEmail($this->input->post('email'));
			$customer->setPhone($this->input->post('phone'));
			$customer->setFax($this->input->post('fax'));
            $customer->setDescription($this->input->post('description'));
            
			$customer->setShippingAddress($this->input->post('shipping_address'));
			$customer->setShippingCity($this->input->post('shipping_city'));
			$customer->setShippingProvinceAbbr($this->input->post('shipping_province_abbr'));
			$customer->setShippingPostal($this->input->post('shipping_postal'));
			
			$customer->setBillingAddress($this->input->post('billing_address'));
			$customer->setBillingCity($this->input->post('billing_city'));
			$customer->setBillingProvinceAbbr($this->input->post('billing_province_abbr'));
			$customer->setBillingPostal($this->input->post('billing_postal'));
			
			$this->em->persist($customer);
			$this->em->flush();
		} else {
			throw new Exception('Customer not exists.');
		}
	}
	
	private function _customer_validate() {
		$customer_validation_rule = array(
			//[0]
			array('field'=>'name',
				  'label'=>'Name',
				  'rules'=>'required|xss_clean|min_length[3]'),
			//[1]
			array('field'=>'email',
				  'label'=>'Email',
				  'rules'=>'valid_email|is_unique[customer\models\Customer.email]'),
			//[2]
			array('field'=>'phone',
				  'label'=>'Phone',
				  'rules'=>'required|valid_phone_number'),
			//[3]
			array('field'=>'fax',
				  'label'=>'Fax',
				  'rules'=>'valid_phone_number'),
            //[4]
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>'max_length[250]'),
			//[6]
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>''),
			//[7]
			array('field'=>'address',
				  'label'=>'Address',
				  'rules'=>''),
			//[8]
			array('field'=>'city',
				  'label'=>'City',
				  'rules'=>''),
			//[9]
			array('field'=>'province_abbr',
				  'label'=>'Province',
				  'rules'=>''),
			//[10]
			array('field'=>'postal',
				  'label'=>'Postal',
				  'rules'=>'')
		);
		
		if($this->input->post('edit')) {
			$customer = $this->em->getRepository('customer\models\Customer')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('email') == $customer->getEmail()) {
				unset($customer_validation_rule[1]);
			}
		}
		
		$this->form_validation->set_rules($customer_validation_rule); 
		return $this->form_validation->run();
	}
}