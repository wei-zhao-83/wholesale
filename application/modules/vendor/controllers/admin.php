<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	private $upload_path = '';
	
	function  __construct() {
		parent::__construct();
		
		$config['upload_path'] = $this->upload_path = $this->config->item('dealer_image_upload_path', 'config_site');
		$config['allowed_types'] = $this->config->item('dealer_image_allowed_types', 'config_site');
		$config['max_size']	= $this->config->item('dealer_image_max_size', 'config_site');
		$config['max_width']  = $this->config->item('dealer_image_max_width', 'config_site');
		$config['max_height']  = $this->config->item('dealer_image_max_height', 'config_site');
		
		$this->load->library('upload', $config);
	}
	
	public function index() {
		$filter = $this->input->post();
		if ($this->input->post('as_values_tags')) {
			$filter['tags'] = explode(',', $this->input->post('as_values_tags'));
		}
		
		$data = array('vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$vendor = new vendor\models\Vendor;
		
		$post_tags = ($this->input->post('as_values_tags'))?trim($this->input->post('as_values_tags')):'';
		
		$data = array('vendor' => $vendor,
					  'post_tags' => $post_tags);
		
		if ($this->_vendor_validate() !== FALSE) {	
			try {
				$this->_do($vendor);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Vendor successfully inserted.'));
				redirect('admin/vendor/edit/' . $vendor->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this vendor.'));
				redirect('admin/vendor');
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
		$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneBy(array('id' => $id));
		
		if (!$vendor) {
			redirect('admin/vendor');
		}
		
		$tags = $this->em->getRepository('tag\models\Tag')->findAll();
		
		$current_tags = array();
		foreach ($vendor->getTags() as $tag) {
			$current_tags[] = $tag->getName();
		}
		
		$data = array('vendor' => $vendor,
					  'tags' => $tags,
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
		$this->load->view('admin/menu');
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
			
			//$this->em->remove($vendor);
			//$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/vendor');
	}
	
	private function _do(vendor\models\Vendor $vendor) {
		if ($vendor instanceof vendor\models\Vendor) {
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
				
				// remove the current image
				$current_images = $vendor->getImages();
				foreach ($current_images as $current_image) {
					$vendor->removeImage($current_image);
					$this->em->remove($current_image);
				}
				
				// update the current images
				if ($this->input->post('current_vendor_images')) {
					foreach ($this->input->post('current_vendor_images') as $id => $value) {
						$image = new image\models\Image;
						$image->setName($value['name']);
						$image->setPath($value['path']);
						$image->setAlt($value['alt']);
						$image->setArrange($value['arrange']);
						$image->setMain($value['main']);
						
						$vendor->addImage($image);
					}
				}
				
				// update the current contacts
				if ($this->input->post('current_vendor_contacts')) {
					foreach ($this->input->post('current_vendor_contacts') as $id => $value) {
						if (!empty($value['name'])) {
							$contact = new dealer\models\DealerContact;
							$contact->setName($value['name']);
							$contact->setPhone($value['phone']);
							$contact->setDirectLine($value['direct_line']);
							$contact->setComment($value['comment']);
							
							$vendor->addContact($contact);
						}
					}
				}
			}
			
			// upload images first to folder
			foreach($_FILES as $key => $value):
				if ($this->upload->do_upload($key)):
					$array_key = (int)str_replace('image_file_', '', $key);
					$vendor_image_array[$array_key] = $this->upload->data();
				endif;
			endforeach;
			
			// add images
			foreach ($this->input->post('vendor_images') as $key => $value) {
				if(isset($vendor_image_array[$key]['file_name'])) {
					$image = new image\models\Image;
					$image->setName($value['name']);
					$image->setPath($this->upload_path . $vendor_image_array[$key]['file_name']);
					$image->setAlt($value['alt']);
					$image->setArrange($value['arrange']);
					$image->setMain($value['main']);
					
					$vendor->addImage($image);
				}
			}
			
			// add selected tags
			$tags = explode(',', $this->input->post('as_values_tags'));
			foreach($tags as $tag_name) {
				$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('name' => $tag_name));
				if ($tag) {
					$vendor->addTag($tag);
				}
			}
			
			// add contacts
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
			
			$vendor->setName($this->input->post('name'));
			$vendor->setEmail($this->input->post('email'));
			$vendor->setPhone($this->input->post('phone'));
			$vendor->setFax($this->input->post('fax'));
			$vendor->setOrderFrequency($this->input->post('order_frequency'));
			$vendor->setHSTNumber($this->input->post('hst_number'));
            $vendor->setDescription($this->input->post('description'));
            
			$vendor->setBankName($this->input->post('bank_name'));
			$vendor->setBankBranch($this->input->post('bank_branch'));
			$vendor->setBankAccount($this->input->post('bank_account'));
			
			$vendor->setShippingAddress($this->input->post('shipping_address'));
			$vendor->setShippingCity($this->input->post('shipping_city'));
			$vendor->setShippingProvinceAbbr($this->input->post('shipping_province_abbr'));
			$vendor->setShippingPostal($this->input->post('shipping_postal'));
			
			$vendor->setBillingAddress($this->input->post('billing_address'));
			$vendor->setBillingCity($this->input->post('billing_city'));
			$vendor->setBillingProvinceAbbr($this->input->post('billing_province_abbr'));
			$vendor->setBillingPostal($this->input->post('billing_postal'));
			
			$this->em->persist($vendor);
			$this->em->flush();
		} else {
			throw new Exception('Vendor not exists.');
		}
	}
	
	private function _vendor_validate() {
		$vendor_validation_rule = array(
			//[0]
			array('field'=>'name',
				  'label'=>'Name',
				  'rules'=>'required|xss_clean|min_length[3]'),
			//[1]
			array('field'=>'email',
				  'label'=>'Email',
				  'rules'=>'valid_email|is_unique[vendor\models\Vendor.email]'),
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
			//[5]
			array('field'=>'order_frequency',
				  'label'=>'Order Frequency',
				  'rules'=>''),
			//[6]
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>''),
			//[7]
			array('field'=>'shipping_address',
				  'label'=>'Shipping Address',
				  'rules'=>''),
			//[8]
			array('field'=>'shipping_city',
				  'label'=>'Shipping City',
				  'rules'=>''),
			//[9]
			array('field'=>'shipping_province_abbr',
				  'label'=>'Shipping Province',
				  'rules'=>''),
			//[10]
			array('field'=>'shipping_postal',
				  'label'=>'Shipping Postal',
				  'rules'=>''),
			//[11]
			array('field'=>'billing_address',
				  'label'=>'Billing Address',
				  'rules'=>''),
			//[12]
			array('field'=>'billing_city',
				  'label'=>'Billing City',
				  'rules'=>''),
			//[13]
			array('field'=>'billing_province_abbr',
				  'label'=>'Billing Province',
				  'rules'=>''),
			//[14]
			array('field'=>'billing_postal',
				  'label'=>'Billing Postal',
				  'rules'=>''),
			array('field'=>'hst_number',
				  'label'=>'HST Number',
				  'rules'=>''),
			array('field'=>'bank_name',
				  'label'=>'Bank Name',
				  'rules'=>''),
			array('field'=>'bank_branch',
				  'label'=>'Bank Branch',
				  'rules'=>''),
			array('field'=>'bank_account',
				  'label'=>'Brank Account',
				  'rules'=>''),
		);
		
		if($this->input->post('edit')) {
			$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('email') == $vendor->getEmail()) {
				unset($vendor_validation_rule[1]);
			}
		}
		
		$this->form_validation->set_rules($vendor_validation_rule); 
		return $this->form_validation->run();
	}
}