<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {		
		$filter = $this->input->post();
		
		$data = array('returns' => $this->em->getRepository('returns\models\Returns')->getReturns($filter),
					  'customers' => $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'statuses' => $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$return = new returns\models\Returns;
		
		try {
			$this->_do($return);
			redirect('admin/returns/edit/' . $return->getId());
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not create this return.'));
		}
		
		redirect('admin/returns/');
	}
	
	public function ajax_refresh_total() {
		$type = $this->input->post('type');
		
		if ($this->input->post('type') && $type == 'ajax') {		
			$products = $this->input->post('products');
			
			$sub_total = $total = $tax = 0;
			
			// Calculate the sub total
			if (!empty($products)) {
				foreach ($products as $prod_id => $prod) {
					$product = $this->em->getRepository('product\models\Product')->findOneById($prod_id);
					
					$sub_total += $prod['return'] * $prod['qty'];
					$tax += $prod['return'] * $this->tax * $prod['qty'];
				}
			}
			
			echo json_encode(array('sub_total' => number_format((float)$sub_total, 2, '.', ''),
									'tax' => number_format((float)($tax), 2, '.', ''),
									'total' => number_format((float)($sub_total + $tax), 2, '.', '')));
		} else {
			redirect('/admin/returns/', 'location', 301);
		}
	}
	
	public function edit($id) {
		$return = $this->em->getRepository('returns\models\Returns')->findOneById($id);
		
		if (!$return) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not find this return - #' . $id));
			redirect('admin/returns');
		}
		
		// Get the selected customer id, default is set to ''. 
		$selected_customer = '';
		if ($this->input->post('customer')) {
			$selected_customer = $this->input->post('customer');
		} else {
			if ($return->getCustomer()) {
				$selected_customer = $return->getCustomer()->getId();
			}
		}
		
		// Get all the current transaction ids, load it in the js localstore $selected_products, when this page is loaded
		$current_transaction_items = $return->getItems();
		$current_product_ids = array();
		if(!empty($current_transaction_items)) {
			foreach ($current_transaction_items as $current_item) {
				$current_product_ids[] = $current_item->getProduct()->getId();
			}
		}
		
		// Get summary
		$summary = $this->_get_summary($return);
		
		// Assign data to the template
		$data = array('return' 			=> $return,
					  'categories'  		=> $this->em->getRepository('category\models\Category')->getCategories(),
					  'customers' 			=> $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'vendors' 			=> $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'selected_customer' 	=> $selected_customer,
					  'summary'				=> $summary,
					  'current_product_ids' => json_encode($current_product_ids),
					  'statuses' 			=> $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		// Form validation
		if ($this->_return_validate() !== FALSE) {
			try {
				$this->_do($return);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'return successfully updated.'));
				redirect('admin/returns/edit/' . $return->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/returns/');
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
			$return = $this->em->getRepository('returns\models\Returns')->findOneById($id);
			
			$return->setDeletedAt(new DateTime);
			$this->em->persist($return);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/returns');
	}
	
	private function _return_validate() {
		$return_validation_rule = array(
			'vendor' => array('field'=>'customer',
							  'label'=>'Customer',
							  'rules'=>''),
			'comment' => array('field'=>'comment',
							   'label'=>'Comment',
							   'rules'=>'xss_clean')
		);
		
		$this->form_validation->set_rules($return_validation_rule); 
		return $this->form_validation->run();
	}
	
	private function _get_summary(returns\models\Returns $return) {
		$sub_total = $tax = 0;
		
		$items = $return->getItems();
		
		if(!empty($items)) {
			foreach($items as $item) {
				$sub_total += $item->getReturn() * $item->getQty();
				$tax += $item->getTax() * $item->getReturn() * $item->getQty();				
			}
		}
		
		return array('sub_total' => number_format((float)$sub_total, 2, '.', ''),
					 'tax' => number_format((float)($tax), 2, '.', ''),
					 'total' => number_format((float)($sub_total + $tax), 2, '.', ''));
	}
	
	private function _do(returns\models\Returns $return) {
		// Set the Customer
		$customer = $this->em->getRepository('customer\models\Customer')->findOneById($this->input->post('customer'));
		
		// Set default transaction status to "Draft"
		$post_status_id = $this->input->post('status');
		$status = $this->em->getRepository('transaction_status\models\TransactionStatus')->findOneById(!empty($post_status_id)? $post_status_id : 1);
		
		if ($this->input->post('edit')) {
			// Remove the current products
			$current_items = $return->getItems();
			
			if (!empty($current_items)) {
				foreach ($current_items as $current_item) {
					$return->removeItem($current_item);
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
				
				$item->setTax($this->tax);
				
				$item->setQty($prod['qty']);
				$item->setReturn($prod['return']);
				$item->setComment($prod['comment']);
				
				$return->addItem($item);
			}
		}
		
		$summary = $this->_get_summary($return);
		
		$return->setTotal($summary['total']);
		$return->setUser($this->current_user);
		$return->setCustomer($customer);
		$return->setStatus($status);
		$return->setComment($this->input->post('comment'));
		
		$this->em->persist($return);
		$this->em->flush();
	}
}