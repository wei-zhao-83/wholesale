<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	private $upload_path = '';
	private $unit_measures = array();
	
	function  __construct() {
		parent::__construct();
		
		$config['upload_path'] = $this->upload_path = $this->config->item('product_image_upload_path', 'config_site');
		$config['allowed_types'] = $this->config->item('product_image_allowed_types', 'config_site');
		$config['max_size']	= $this->config->item('product_image_max_size', 'config_site');
		$config['max_width']  = $this->config->item('product_image_max_width', 'config_site');
		$config['max_height']  = $this->config->item('product_image_max_height', 'config_site');
		
		$this->load->library('upload', $config);
		
		$temp_unit_measures = product\models\Product::getUOM();
		foreach ($temp_unit_measures as $measure) {
			$this->unit_measures[$measure] = get_full_name($measure); 
		}
	}
	
	public function view_history() {
		$id = $this->uri->segment(4);
		$timestamp = $this->uri->segment(5);
		
		if ($id && $timestamp) {
			$product = $this->em->getRepository('product\models\Product')->findOneBy(array('id' => $id));
			$changes =	$product->getProductChange($timestamp);

			$this->load->view('admin/view', array('changes' => $changes->getChanges(),
												  'timestamp' => $changes->getTimeStamp()));
		}
	}
	
	public function ajax_search() {
		$products = $return = array();
		
		if ($this->input->post('search')) {
			$filter = array_filter($this->input->post('search'));
		}
		
		if (!empty($filter)) {
			$products = $this->em->getRepository('product\models\Product')->getproducts($filter);
			$selected_product_ids = explode(',', $this->input->post('selected_products'));
			
			foreach ($products as $product) {
				// dont return selected products
				if (!in_array($product->getId(), $selected_product_ids)) {
					$return[$product->getId()] = array('name' => $product->getName(),
														'id' => $product->getId(),
														'category' => $product->getCategory()->getName(),
														'barcode' => $product->getBarcode(),
														'sku' => $product->getSKU(),
														'cost' => $product->getCost(),
														'suggested_price' => $product->getSuggestedPrice(),
														'no_service_price' => $product->getNoServicePrice(),
														'full_service_price' => $product->getFullServicePrice(),
														'cash_and_carry' => $product->getCNC(),
														'qty_unit' => $product->getQtyUnit(),
														'qty' => $product->getTotalQty(),
														'unit' => $product->getUnit(),
														'order_frequency' => 0,
														'num_of_pending' => 0);
				}
			}
			
			// pending products
			if (!empty($return)) {
				$_product_pending = $this->em->getRepository('purchase\models\Purchase')->getSalePendingProd(array_keys($return));
				
				if (!empty($_product_pending)) {
					foreach($_product_pending as $_prod_id => $_number_of_pending) {
						if(!empty($return[$_prod_id])) {
							$return[$_prod_id]['num_of_pending'] = '-' . $_number_of_pending;
						}
					}
				}
			}
			
			// order frequency
			if(!empty($return) && $this->input->post('vendor_id')) {
				$selected_vendor = $this->em->getRepository('vendor\models\Vendor')->findOneById($this->input->post('vendor_id'));
				$_order_frequency = $this->em->getRepository('purchase\models\Purchase')->getOrderFrequency($selected_vendor, array_keys($return));
				
				if (!empty($_order_frequency)) {
					foreach($_order_frequency as $_prod_id => $_number_of_order) {
						if(!empty($return[$_prod_id])) {
							$return[$_prod_id]['order_frequency'] = $_number_of_order;
						}
					}
				}
			}
		}
		
		echo json_encode($return);
	}
	
	public function index() {
		// Filter setting
		if ($_POST) {
			$_filter = $this->input->post();
			
			if ($this->input->post('as_values_tags')) {
				$_filter['tags'] = explode(',', $this->input->post('as_values_tags'));
			}
			
			// Set current filter to session
			$this->session->set_userdata(array('product_filter' => $_filter));
		}
		
		$filter = $this->session->userdata('product_filter');
		
		// Grab the current page number from url instead of session
		$filter['current_page'] = $this->uri->segment(4, 0);
		
		if (empty($filter['per_page'])) {
			$filter['per_page'] = $this->config->item('per_page', 'config_site');
		}
		
		// Get all the products by filter
		$products = $this->em->getRepository('product\models\Product')->getproducts($filter);
		
		$_per_page = !empty($filter['per_page']) ? $filter['per_page'] : false;
		$pagination	= create_pagination('admin/product/index/', count($products), $_per_page);		
		
		$data = array('categories'  	=> $this->em->getRepository('category\models\Category')->getCategories(),
					  'products' 		=> $products,
					  'vendors' 		=> $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'pagination'		=> $pagination,
					  'filter' 			=> $filter);
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$product = new product\models\Product;
		
		$post_tags = ($this->input->post('as_values_tags'))?trim($this->input->post('as_values_tags')):'';
		
		$data = array('product' => $product,
					  'categories' => $this->em->getRepository('category\models\Category')->getCategories(),
					  'vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'post_tags' => $post_tags,
					  'unit_measures' => $this->unit_measures);
		
		if ($this->_product_validate() !== FALSE) {	
			try {
				$this->_do($product);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Product successfully inserted.'));
				redirect('admin/product/edit/' . $product->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this product.'));
				redirect('admin/product');
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
		$product = $this->em->getRepository('product\models\Product')->findOneBy(array('id' => $id));
		
		if (!$product) {
			redirect('admin/product');
		}
		
		$tags = $this->em->getRepository('tag\models\Tag')->findAll();
		
		$current_tags = array();
		foreach ($product->getTags() as $tag) {
			$current_tags[] = $tag->getName();
		}
		
		$_selected_vendors = $product->getVendors();
		$selected_vendor = !empty($_selected_vendors) ? $_selected_vendors[0] : false;
		
		$data = array('product' => $product,
					  'histories' => $product->getProductChanges(),
					  'tags' => $tags,
					  'categories' => $this->em->getRepository('category\models\Category')->getCategories(),
					  'vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'current_tags' => implode(',', $current_tags),
					  'selected_vendor' => $selected_vendor,
					  'unit_measures' => $this->unit_measures);
		
		if ($this->_product_validate() !== FALSE) {
			try {
				$this->_do($product);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Product successfully updated.'));
				redirect('admin/product/edit/' . $product->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/product');
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
			$product = $this->em->getRepository('product\models\Product')->findOneBy(array('id' => $id));
			
			// Soft Delete
			$product->setDeletedAt(new DateTime);
			$this->em->persist($product);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/product');
	}
	
	private function _do(product\models\Product $product) {
		if ($product instanceof product\models\Product) {
			
			$category = $this->em->getRepository('category\models\Category')->findOneBy(array('id' => $this->input->post('category')));
			
            if ($this->input->post('edit')) {
				// save history
				$changes = array();
				$changes['content'] = $this->input->post();
				$changes['user']= array('id' => $this->current_user->getId(), 'username' => $this->current_user->getUsername());
				
				$history = new product\models\ProductHistory;
				$history->setTimeStamp(time());
				$history->setChanges($changes);
				
				$product->addProductChange($history);
				
				// remove the current tags
				$current_tags = $product->getTags();
				foreach ($current_tags as $current_tag) {
					$product->removeTag($current_tag);
				}
				
				// remove the current image
				$current_images = $product->getImages();
				foreach ($current_images as $current_image) {
					$product->removeImage($current_image);
					$this->em->remove($current_image);
				}
				
				// update the current images
				if ($this->input->post('current_product_images')) {
					foreach ($this->input->post('current_product_images') as $id => $value) {
						$image = new image\models\Image;
						$image->setName($value['name']);
						$image->setPath($value['path']);
						$image->setAlt($value['alt']);
						$image->setArrange($value['arrange']);
						$image->setMain($value['main']);
						
						$product->addImage($image);
					}
				}
				
				// remove the current vendor
				$current_vendors = $product->getVendors();
				foreach ($current_vendors as $current_vendor) {
					$product->removeVendor($current_vendor);
					$current_vendor->removeProduct($product);
				}
			}
			
			// add and update vendor
			$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneById($this->input->post('product_vendor'));
			$vendor->addProduct($product);
			$product->addVendor($vendor);
			
			// upload images first to folder
			foreach($_FILES as $key => $value):
				if ($this->upload->do_upload($key)):
					$array_key = (int)str_replace('image_file_', '', $key);
					$product_image_array[$array_key] = $this->upload->data();
				endif;
			endforeach;
			
			// add images
			foreach ($this->input->post('product_images') as $key => $value) {
				if(isset($product_image_array[$key]['file_name'])) {
					$image = new image\models\Image;
					$image->setName($value['name']);
					$image->setPath($this->upload_path . $product_image_array[$key]['file_name']);
					$image->setAlt($value['alt']);
					$image->setArrange($value['arrange']);
					$image->setMain($value['main']);
					
					$product->addImage($image);
				}
			}
			
			// add selected tags
			$tags = explode(',', $this->input->post('as_values_tags'));
			foreach($tags as $tag_name) {
				$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('name' => $tag_name));
				if ($tag) {
					$product->addTag($tag);
				}
			}
			
			// add category
			$product->setCategory($category);
			
			$product->setName($this->input->post('name'));
			$product->setBarcode($this->input->post('barcode'));
			$product->setSKU($this->input->post('sku'));
			$product->setSection($this->input->post('section'));
			$product->setActive($this->input->post('active'));
			$product->setDescription($this->input->post('description'));
			$product->setComment($this->input->post('comment'));
			
			$product->setCost($this->input->post('cost'));
			$product->setSuggestedPrice($this->input->post('suggested_price'));
			$product->setNoServicePrice($this->input->post('no_service_price'));
			$product->setFullServicePrice($this->input->post('full_service_price'));
			$product->setDiscount($this->input->post('discount'));
			$product->setCNC($this->input->post('cash_and_carry'));
            
			$product->setTotalQty($this->input->post('total_qty'));
			$product->setQtyUnit($this->input->post('qty_unit'));
			$product->setUnit($this->input->post('unit'));
			$product->setUnitCase($this->input->post('unit_case'));
			
			$this->em->persist($product);
			$this->em->flush();
		} else {
			throw new Exception('Product not exists.');
		}
	}
	
	private function _product_validate() {
		$product_validation_rule = array(
			array('field'=>'name',
				  'label'=>'Name',
				  'rules'=>'required|xss_clean|min_length[3]'),
			array('field'=>'barcode',
				  'label'=>'Barcode',
				  'rules'=>''),
			array('field'=>'sku',
				  'label'=>'SKU',
				  'rules'=>''),
			array('field'=>'section',
				  'label'=>'Section',
				  'rules'=>''),
			array('field'=>'active',
				  'label'=>'Active',
				  'rules'=>'required'),
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>''),
			array('field'=>'comment',
				  'label'=>'Comment',
				  'rules'=>''),
			array('field'=>'cost',
				  'label'=>'Cost',
				  'rules'=>'is_money'),
			array('field'=>'suggested_price',
				  'label'=>'Suggested Retail Price',
				  'rules'=>'is_money'),
			array('field'=>'no_service_price',
				  'label'=>'No Service Price',
				  'rules'=>'is_money'),
			array('field'=>'full_service_price',
				  'label'=>'Full Service Price',
				  'rules'=>'is_money'),
			array('field'=>'discount',
				  'label'=>'Discount',
				  'rules'=>'is_money'),
			array('field'=>'cash_and_carry',
				  'label'=>'CNC',
				  'rules'=>'is_money'),
			array('field'=>'total_qty',
				  'label'=>'Total Qty',
				  'rules'=>''),
			array('field'=>'qty_unit',
				  'label'=>'Qty./Unit',
				  'rules'=>''),
			array('field'=>'unit',
				  'label'=>'Unit',
				  'rules'=>''),
			array('field' => 'unit_pack',
				  'label' => 'Unit / Pack',
				  'rules'=>''),
			array('field' => 'pack_case',
				  'label' => 'Pack / Case',
				  'rules'=>''),
			array('field'=>'category',
				  'label'=>'Category',
				  'rules'=>'required'),
			array('field'=>'product_vendor',
				  'label'=>'Vendor',
				  'rules'=>'required')
		);
		
		if($this->input->post('edit')) {
			$product = $this->em->getRepository('product\models\Product')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('barcode') == $product->getBarcode()) {
				unset($product_validation_rule[1]);
			}
			
			if($this->input->post('sku') == $product->getSKU()) {
				unset($product_validation_rule[2]);
			}
		}
		
		$this->form_validation->set_rules($product_validation_rule); 
		return $this->form_validation->run();
	}
}