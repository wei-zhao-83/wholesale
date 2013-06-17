<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$filter = $this->input->post();
		
		$data = array('purchases' => $this->em->getRepository('purchase\models\Purchase')->getPurchases($filter),
					  'vendors' => $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'statuses' => transaction\models\Transaction::getPurchaseStatuses());
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create($vendor_id) {
		$vendor = null;
		$purchase = new purchase\models\Purchase;
		
		try {
			$vendor = $this->em->getRepository('vendor\models\Vendor')->findOneById($vendor_id);
			
			$purchase->setVendor($vendor);
			$this->_do($purchase);
			
			redirect('admin/purchase/edit/' . $purchase->getId());
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not create this purchase.'));
		}
		
		redirect('admin/purchase/');
	}
	
	public function edit($id) {
		$purchased_items = $products = $data = $frequency = array();
		
		try {
			$purchase = $this->em->getRepository('purchase\models\Purchase')->findOneById($id);
			if (!$purchase) {
				throw new Exception('Can not find this purchase - #' . $id);
			}
			
			$products = $this->em->getRepository('product\models\Product')->getProducts(array('vendor' => $purchase->getVendor()->getID()));
			
			// Order Frequency
			$frequency = $this->em->getRepository('purchase\models\Purchase')->getOrderFrequency($purchase->getVendor());			
			
			// Get purchased items
			$current_items = $purchase->getItems();
			foreach ($current_items as $item) {
				$purchased_items[$item->getProduct()->getID()] = $item;			
			}
			
			// Form validation
			if ($this->_purchase_validate() !== FALSE) {
				$this->_do($purchase);
				$data['message'] = array('type' => 'success', 'content' => 'Successfully updated.');
			}
			
			if ($this->form_validation->error_array()) {
				$data['message'] = array('type' => 'error', 'content' => $this->form_validation->error_array());
			}
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
			redirect('admin/purchase');
		}
		
		if ($this->form_validation->error_array()) {
			$data['message'] = array('type' => 'error', 'content' => $this->form_validation->error_array());
		}
		
		// Assign data to the template
		$data += array('purchase' 			=> $purchase,
					  'products'			=> $products,
					  'frequency'			=> $frequency,
					  'purchased_items'		=> $purchased_items,
					  'statuses' 			=> transaction\models\Transaction::getPurchaseStatuses());
		
		$this->load->view('admin/header');
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
	
	private function _purchase_validate() {
		$purchase_validation_rule = array(
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
	
	private function _do(purchase\models\Purchase $purchase) {
		// Set default transaction status to "Draft"
		$status = ($this->input->post('status')) ? $this->input->post('status') : transaction\models\Transaction::STATUS_DRAFT;
		
		$purchase->setUser($this->current_user);
		$purchase->setStatus($status);
		$purchase->setComment($this->input->post('comment'));
		
		$current_items = $purchase->getItems();
		$products = $this->input->post('products');
		
		if (!empty($current_items)) {
			foreach($current_items as $current_item) {
				// Update the current items, if current item exists in the post products array
				if(isset($products[$current_item->getProduct()->getID()]) && $products[$current_item->getProduct()->getID()]['qty'] > 0) {
					$current_item->setQty($products[$current_item->getProduct()->getID()]['qty']);
					$current_item->setComment($products[$current_item->getProduct()->getID()]['comment']);	
					
					$this->em->persist($current_item);
					
					// Unset it from the post products array
					unset($products[$current_item->getProduct()->getID()]);
				// Delete the removed items
				} else {
					$purchase->removeItem($current_item);
					$this->em->remove($current_item);
				}
			}
		}
		
		// Add new item
		if (!empty($products)) {
			foreach ($products as $prod_id => $prod) {
				if ($prod['qty'] > 0) {
					$product = $this->em->getRepository('product\models\Product')->findOneById($prod_id);
				
					$item = new transaction\models\TransactionItem;
					
					$item->setProduct($product);
					$item->setCost($product->getCost());
					$item->setTax($this->tax);
					$item->setQty($prod['qty']);
					$item->setComment($prod['comment']);
					
					$purchase->addItem($item);
				}
			}
		}
		
		$summary = $purchase->getSummary();
		$purchase->setTotal($summary['total']);
		
		$this->em->persist($purchase);
		$this->em->flush();
	}
}