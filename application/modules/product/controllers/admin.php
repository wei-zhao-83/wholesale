<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	private $unit_measures = array();
	
	function  __construct() {
		parent::__construct();
		
		$this->load->model('ProductsFilter');
		
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
														'no_discount' => $product->getNoDiscount(),
														'active' => $product->getActive(),
														'category' => $product->getCategory()->getName(),
														'barcode' => $product->getBarcode(),
														'sku' => $product->getSKU(),
														'cost' => $product->getCost(),
														'suggested_price' => $product->getSuggestedPrice(),
														'standard_service' => $product->getNoServicePrice(),
														'full_service' => $product->getFullServicePrice(),
														'cash_and_carry' => $product->getCNC(),
														'qty_unit' => $product->getQtyUnit(),
														'qty' => $product->getTotalQty(),
														'unit' => $product->getUnit());
														//'order_frequency' => 0,
														//'num_of_pending' => 0);
				}
			}
			
			// pending products
			//if (!empty($return)) {
			//	$_product_pending = $this->em->getRepository('purchase\models\Purchase')->getSalePendingProd(array_keys($return));
			//	
			//	if (!empty($_product_pending)) {
			//		foreach($_product_pending as $_prod_id => $_number_of_pending) {
			//			if(!empty($return[$_prod_id])) {
			//				$return[$_prod_id]['num_of_pending'] = '-' . $_number_of_pending;
			//			}
			//		}
			//	}
			//}
			
			// order frequency
			//if(!empty($return) && $this->input->post('vendor_id')) {
			//	$selected_vendor = $this->em->getRepository('vendor\models\Vendor')->findOneById($this->input->post('vendor_id'));
			//	$_order_frequency = $this->em->getRepository('purchase\models\Purchase')->getOrderFrequency($selected_vendor, array_keys($return));
			//	
			//	if (!empty($_order_frequency)) {
			//		foreach($_order_frequency as $_prod_id => $_number_of_order) {
			//			if(!empty($return[$_prod_id])) {
			//				$return[$_prod_id]['order_frequency'] = $_number_of_order;
			//			}
			//		}
			//	}
			//}
		}
		
		echo json_encode($return);
	}
	
	public function index() {
		$this->load->model('ProductsFilter');
		
		$filter = new ProductsFilter($_GET);
		$filter->setCurrentPage($this->uri->segment(4, 0));
		if (!empty($_GET['as_values_tags'])) {
			$filter->setTags($_GET['as_values_tags']);
		}
		
		$products = $this->em->getRepository('product\models\Product')->getProducts($filter->toArray());
		
		// Pagination
		$pagination	= create_pagination('admin/product/index/', count($products), $filter->toArray());
		
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
		
		$data = array('product' => $product,
					  'categories' => $this->em->getRepository('category\models\Category')->getCategories(),
					  'vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors(),
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
		if ($this->input->post('edit')) {
			// save history
			$changes = array();
			$changes['content'] = $this->input->post();
			$changes['user']= array('id' => $this->current_user->getId(), 'username' => $this->current_user->getUsername());
			
			// Add product change log
			$history = new product\models\ProductHistory;
			$history->setTimeStamp(time());
			$history->setChanges($changes);
			
			$product->addProductChange($history);
			
			// remove the current tags
			$current_tags = $product->getTags();
			foreach ($current_tags as $current_tag) {
				$product->removeTag($current_tag);
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
		
		// Add selected tags
		$tags = explode(',', $this->input->post('as_values_tags'));
		foreach($tags as $tag_name) {
			$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('name' => $tag_name));
			if ($tag) {
				$product->addTag($tag);
			}
		}
		
		// Add category and unset category post
		$category = $this->em->getRepository('category\models\Category')->findOneBy(array('id' => $this->input->post('category')));
		$product->setCategory($category);
		unset($_POST['category']);
		
		// Reflection object
		$reflected_model = new ReflectionClass('product\models\Product');
		
		foreach($this->input->post() as $field => $value) {
			$_method = 'set' . implode(array_map('ucfirst', explode('_', $field)));
			
			if ($reflected_model->hasMethod($_method)) {
				$product->$_method($value);
			}
		}
		
		$this->em->persist($product);
		$this->em->flush();
	}
	
	private function _product_validate() {
		$_rules = $this->config->item('rule_product', 'validate');
		
		if($this->input->post('edit')) {
			$product = $this->em->getRepository('product\models\Product')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('barcode') == $product->getBarcode()) {
				unset($_rules['barcode']);
			}
			
			if($this->input->post('sku') == $product->getSKU()) {
				unset($_rules['sku']);
			}
		}
		
		$this->form_validation->set_rules($_rules); 
		return $this->form_validation->run();
	}
}