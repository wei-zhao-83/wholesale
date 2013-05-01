<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {		
		$filter = $this->input->post();
		
		$data = array('sales' => $this->em->getRepository('sale\models\Sale')->getSales($filter),
					  'customers' => $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'statuses' => $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$sale = new sale\models\Sale;
		
		try {
			$this->_do($sale);
			redirect('admin/sale/edit/' . $sale->getId());
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not create this sale.'));
		}
		
		redirect('admin/sale/');
	}
	
	public function ajax_refresh_total() {
		$type = $this->input->post('type');
		
		if ($this->input->post('type') && $type == 'ajax') {
			$products = $this->input->post('products');
			
			$sub_total = $total = $tax = $discount = 0;
			
			// Calculate the sub total
			if (!empty($products)) {
				foreach ($products as $prod_id => $prod) {
					$product = $this->em->getRepository('product\models\Product')->findOneById($prod_id);
					
					$sub_total += $product->getCost() * $prod['qty'];
					$discount += $prod['discount'] * $prod['qty'];
					$tax += ($product->getCost() - $prod['discount']) * $this->tax * $prod['qty'];					
				}
			}
			
			echo json_encode(array('sub_total' => number_format((float)$sub_total, 2, '.', ''),
									'tax' => number_format((float)($tax), 2, '.', ''),
									'discount' => number_format((float)($discount), 2, '.', ''),
									'total' => number_format((float)($sub_total - $discount + $tax), 2, '.', '')));
		} else {
			redirect('/admin/sale/', 'location', 301);
		}
	}
	
	public function edit($id) {
		$sale = $this->em->getRepository('sale\models\Sale')->findOneById($id);
		
		if (!$sale) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not find this sale - #' . $id));
			redirect('admin/sale');
		}
		
		// Get the selected customer id, default is set to ''. 
		$selected_customer = '';
		if ($this->input->post('customer')) {
			$selected_customer = $this->input->post('customer');
		} else {
			if ($sale->getCustomer()) {
				$selected_customer = $sale->getCustomer()->getId();
			}
		}
		
		// Get all the current transaction ids, load it in the js localstore $selected_products, when this page is loaded
		$current_transaction_items = $sale->getItems();
		$current_product_ids = array();
		if(!empty($current_transaction_items)) {
			foreach ($current_transaction_items as $current_item) {
				$current_product_ids[] = $current_item->getProduct()->getId();
			}
		}
		
		// Get summary
		$summary = $this->_get_summary($sale);
		
		// Assign data to the template
		$data = array('sale' 				=> $sale,
					  'categories'  		=> $this->em->getRepository('category\models\Category')->getCategories(),
					  'customers' 			=> $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'vendors'				=> $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'selected_customer' 	=> $selected_customer,
					  'summary'				=> $summary,
					  'types'				=> sale\models\Sale::getTypes(),
					  'payment_types'		=> sale\models\Sale::getPaymentTypes(),
					  'current_product_ids' => json_encode($current_product_ids),
					  'statuses' 			=> $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		// Form validation
		if ($this->_sale_validate() !== FALSE) {
			try {
				$this->_do($sale);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Sale successfully updated.'));
				redirect('admin/sale/edit/' . $sale->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/sale/');
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
			$sale = $this->em->getRepository('sale\models\Sale')->findOneById($id);
			
			$sale->setDeletedAt(new DateTime);
			$this->em->persist($sale);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/sale');
	}
	
	private function _sale_validate() {
		$sale_validation_rule = array(
			'customer' => array('field'=>'customer',
							  'label'=>'Customer',
							  'rules'=>''),
			'comment' => array('field'=>'comment',
							   'label'=>'Comment',
							   'rules'=>'xss_clean')
		);
		
		$this->form_validation->set_rules($sale_validation_rule); 
		return $this->form_validation->run();
	}
	
	private function _get_summary(sale\models\Sale $sale) {
		$sub_total = $discount = $tax = 0;
		
		$items = $sale->getItems();
		
		if(!empty($items)) {
			foreach($items as $item) {
				$sub_total += $item->getCost() * $item->getQty();
				$discount += $item->getDiscount() * $item->getQty();
				$tax += $item->getTax() * ($item->getCost() - $item->getDiscount()) * $item->getQty();
			}
		}
		
		return array('sub_total' => number_format((float)$sub_total, 2, '.', ''),
					 'discount' => number_format((float)($discount), 2, '.', ''),
					 'tax' => number_format((float)($tax), 2, '.', ''),
					 'total' => number_format((float)($sub_total - $discount + $tax), 2, '.', ''));
	}
	
	private function _do(sale\models\Sale $sale) {
		// Set the customer
		$customer = $this->em->getRepository('customer\models\Customer')->findOneById($this->input->post('customer'));
		
		// Set default transaction status to "Draft"
		$post_status_id = $this->input->post('status');
		$status = $this->em->getRepository('transaction_status\models\TransactionStatus')->findOneById(!empty($post_status_id)? $post_status_id : 1);
		
		if ($this->input->post('edit')) {
			// Remove the current products
			$current_items = $sale->getItems();
			if (!empty($current_items)) {
				foreach ($current_items as $current_item) {
					$sale->removeItem($current_item);
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
				$item->setSuggestedPrice($product->getSuggestedPrice());
				$item->setNoServicePrice($product->getNoServicePrice());
				$item->setFullServicePrice($product->getFullServicePrice());
				$item->setCNC($product->getCNC());
				$item->setTax($this->tax);
				
				$item->setQty($prod['qty']);
				$item->setComment($prod['comment']);
				$item->setDiscount($prod['discount']);
				
				$sale->addItem($item);
			}
		}
		
		$summary = $this->_get_summary($sale);
		
		$sale->setTotal($summary['total']);
		$sale->setUser($this->current_user);
		$sale->setCustomer($customer);
		if ($this->input->post('payment')) {
			$sale->setPayment($this->input->post('payment'));
		}
		if ($this->input->post('type')) {
			$sale->setType($this->input->post('type'));
		}
		$sale->setStatus($status);
		$sale->setComment($this->input->post('comment'));
		
		$this->em->persist($sale);
		$this->em->flush();
	}
}