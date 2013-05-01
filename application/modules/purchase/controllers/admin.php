<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$filter = $this->input->post();
		
		$data = array('purchases' => $this->em->getRepository('purchase\models\Purchase')->getPurchases($filter),
					  'vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'statuses' => $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$purchase = new purchase\models\Purchase;
		
		try {
			$this->_do($purchase);
			redirect('admin/purchase/edit/' . $purchase->getId());
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not create this purchase.'));
		}
		
		redirect('admin/purchase/');
	}
	
	public function edit($id) {
		$purchase = $this->em->getRepository('purchase\models\Purchase')->findOneById($id);
		
		if (!$purchase) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not find this purchase - #' . $id));
			redirect('admin/purchase');
		}
		
		// Get the selected vendor. 
		if ($this->input->post('vendor')) {
			$selected_vendor = $this->em->getRepository('vendor\models\Vendor')->findOneById($this->input->post('vendor'));
		} else {
			$selected_vendor = $purchase->getVendor();
		}		
		
		// Get all the current product ids, load it in the js localstore $selected_products, when this page is loaded
		$current_transaction_items = $purchase->getItems();
		$current_product_ids = array();
		if(!empty($current_transaction_items)) {
			foreach ($current_transaction_items as $current_item) {
				$current_product_ids[] = $current_item->getProduct()->getId();
			}
		}
		
		// Get summary
		$summary = $this->_get_summary($purchase);
		
		// Assign data to the template
		$data = array('purchase' 			=> $purchase,
					  'selected_vendor' 	=> $selected_vendor,
					  'categories'  		=> $this->em->getRepository('category\models\Category')->getCategories(),
					  'vendors' 			=> $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'summary'				=> $summary,
					  'current_product_ids' => json_encode($current_product_ids),
					  'statuses' 			=> $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses(),
					  'product_frequency' 	=> $this->em->getRepository('purchase\models\Purchase')->getOrderFrequency($selected_vendor, $current_product_ids),
					  'product_pending' 	=> $this->em->getRepository('purchase\models\Purchase')->getSalePendingProd($current_product_ids));
		
		// Form validation
		if ($this->_purchase_validate() !== FALSE) {
			try {
				$this->_do($purchase);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Purchase successfully updated.'));
				redirect('admin/purchase/edit/' . $purchase->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/purchase/');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function delete() {
		$id = $this->uri->segment(4);
		
		try {
			$purchase = $this->em->getRepository('purchase\models\Purchase')->findOneById($id);
			
			$purchase->setDeletedAt(new DateTime);
			$this->em->persist($purchase);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/purchase');
	}
	
	public function ajax_refresh_total() {
		$type		= $this->input->post('type');
		$products 	= $this->input->post('products');
		
		$status = 200;
		$result = $errors = array();
		$sub_total = $total = $tax = 0;
		
		if ($products && $type && $type == 'ajax') {
			$_products = $this->em->getRepository('product\models\Product')->getProducts(array('ids' => array_keys($products)));
			
			foreach ($_products as $_product) {
				$_qty = (!empty($products[$_product->getId()]['qty'])) ? $products[$_product->getId()]['qty'] : 1;
				
				$sub_total += $_product->getCost() * $_qty;
				$tax += $_product->getCost() * $this->tax * $_qty;				
			}
			
			$result = array('sub_total' => number_format((float)$sub_total, 2, '.', ''),
							'tax' => number_format((float)($tax), 2, '.', ''),
							'total' => number_format((float)($sub_total + $tax), 2, '.', ''));
		} else {
			$status = 400;
			
			if (!$type) { $errors[] = 'Not Ajax call'; }
			if (!$products) { $errors[] = 'No products sent'; }
		}
		
		echo json_encode(array('status' => $status,
							   'result' => $result,
							   'errors' => $errors));
	}
	
	private function _purchase_validate() {
		$purchase_validation_rule = array(
			'vendor' => array('field'=>'vendor',
							  'label'=>'Vendor',
							  'rules'=>''),
			'status' => array('field'=>'status',
							  'label'=>'Status',
							  'rules'=>''),
			'comment' => array('field'=>'comment',
							   'label'=>'Comment',
							   'rules'=>'xss_clean')
		);
		
		$this->form_validation->set_rules($purchase_validation_rule); 
		return $this->form_validation->run();
	}
	
	private function _get_summary(purchase\models\Purchase $purchase) {
		$sub_total = $discount = $tax = 0;
		
		$items = $purchase->getItems();
		
		if(!empty($items)) {
			foreach($items as $item) {
				$sub_total += $item->getCost() * $item->getQty();
				$tax += $item->getTax() * $item->getCost() * $item->getQty();				
			}
		}
		
		return array('sub_total' => number_format((float)$sub_total, 2, '.', ''),
					 'tax' => number_format((float)($tax), 2, '.', ''),
					 'total' => number_format((float)($sub_total + $tax), 2, '.', ''));
	}
	
	private function _do(purchase\models\Purchase $purchase) {
		// Set the Vendor
		$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneById($this->input->post('vendor'));
		
		// Set default transaction status to "Draft"
		$post_status_id = $this->input->post('status');
		$status = $this->em->getRepository('transaction_status\models\TransactionStatus')->findOneById(!empty($post_status_id)? $post_status_id : 1);
		
		if ($this->input->post('edit')) {
			// Remove the current products
			$current_items = $purchase->getItems();
			if (!empty($current_items)) {
				foreach ($current_items as $current_item) {
					$purchase->removeItem($current_item);
					$this->em->remove($current_item);
				}
			}
		}
		
		// Set the products
		$products = $this->input->post('products');
		if (!empty($products)) {
			foreach ($products as $prod_id => $prod) {
				$product = $this->em->getRepository('product\models\Product')->findOneById($prod_id);
				
				$item = new transaction\models\TransactionItem;
				$item->setProduct($product);
				$item->setCost($product->getCost());
				$item->setSuggestedPrice($product->getSuggestedPrice());
				$item->setNoServicePrice($product->getNoServicePrice());
				$item->setFullServicePrice($product->getFullServicePrice());
				$item->setCNC($product->getCNC());
				
				$item->setTax($this->tax);
				
				$item->setQty($prod['qty']);
				$item->setComment($prod['comment']);
				
				$purchase->addItem($item);
			}
		}
		
		$summary = $this->_get_summary($purchase);
		
		$purchase->setTotal($summary['total']);
		$purchase->setUser($this->current_user);
		$purchase->setVendor($vendor);
		$purchase->setStatus($status);
		$purchase->setComment($this->input->post('comment'));
		
		$this->em->persist($purchase);
		$this->em->flush();
	}
}