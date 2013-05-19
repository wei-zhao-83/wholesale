<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {		
		$filter = $this->input->post();
		
		$data = array('sales' 	  => $this->em->getRepository('sale\models\Sale')->getSales($filter),
					  'customers' => $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'statuses'  => $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		$this->load->view('admin/header');
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
		
		// Assign data to the template
		$data = array('sale' 				=> $sale,
					  'selected_customer' 	=> $selected_customer,
					  'categories'  		=> $this->em->getRepository('category\models\Category')->getCategories(),
					  'customers' 			=> $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'vendors'				=> $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'statuses' 			=> $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses(),
					  'types'				=> sale\models\Sale::getTypes(),
					  'payment_types'		=> sale\models\Sale::getPaymentTypes());
		
		// Form validation
		if ($this->_sale_validate() !== FALSE) {
			try {
				$this->_do($sale);
				$data['message'] = array('type' => 'success', 'content' => 'Successfully updated.');
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/sale/');
			}
		}
		
		if ($validation_error = $this->form_validation->error_array()) {
			$data += array('message' => array('type' => 'error', 'content' => $validation_error));
		}		
		
		$this->load->view('admin/header');
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
				$item->setCost($product->getCost());
				$item->setSuggestedPrice($product->getSuggestedPrice());				
				
				if ($this->input->post('type') == sale\models\Sale::TYPE_CNC) {
					$item->setCNC($product->getCNC());
				} elseif ($this->input->post('type') == sale\models\Sale::TYPE_FULL) {
					$item->setFullServicePrice($product->getFullServicePrice());
				} elseif ($this->input->post('type') == sale\models\Sale::TYPE_STANDARD) {
					$item->setNoServicePrice($product->getNoServicePrice());
				}
				
				$item->setTax($this->tax);
				
				$item->setQty($prod['qty']);
				$item->setComment($prod['comment']);
				
				if (!$product->getNoDiscount()) {
					$item->setDiscount($prod['discount']);
				}
				
				$sale->addItem($item);
			}
		}
		
		$summary = $sale->getSummary();
		
		$sale->setTotal($summary['total']);
		$sale->setUser($this->current_user);
		$sale->setCustomer($customer);
		$sale->setStatus($status);
		
		if ($_POST) {
			$sale->setDefaultDiscount($this->input->post('default_discount'));
			$sale->setPayment($this->input->post('payment'));
			$sale->setType($this->input->post('type'));
			$sale->setComment($this->input->post('comment'));
		}
		
		$this->em->persist($sale);
		$this->em->flush();
	}
}