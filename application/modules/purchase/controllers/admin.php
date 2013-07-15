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
	
	public function credit($id) {
		try {
			$results = array();
			
			$settings = $this->em->getRepository('setting\models\Setting');
			$purchase = $this->em->getRepository('purchase\models\Purchase')->findOneById($id);
			
			$items = $purchase->getItems();
			
			foreach($items as $item) {
				$different = $item->getQty() - $item->getReceived();
				
				if ($different > 0) {
					$results[] = $item;
				}
			}
			
			$data = array('purchase' => $purchase,
						  'items' => $results,
						  'settings' => $settings,
						  'company' => $settings->findOneByName('company')->getValue());
			
			$this->load->view('admin/credit', $data);
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
			redirect('admin/purchase/edit/' . $id);
		}		
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
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/vendor');
	}	
	
	public function edit($id) {
		$purchased_items = $products = $data = $frequency = array();
		$ytd = 0;
		
		try {
			// Get the purchase by id
			$purchase = $this->em->getRepository('purchase\models\Purchase')->findOneById($id);
			
			// Get the YTD
			$ytd = $this->em->getRepository('purchase\models\Purchase')->getYtd($purchase->getVendor());
			
			if (!$purchase) {
				throw new Exception('Can not find this purchase - #' . $id);
			}
			
			if ($_POST) {
				// Form validation
				if ($this->_purchase_validate() !== FALSE) {
					$this->_do($purchase);
					$data['message'] = array('type' => 'success', 'content' => 'Successfully updated.');
				}
				
				if ($this->form_validation->error_array()) {
					$data['message'] = array('type' => 'error', 'content' => $this->form_validation->error_array());
				}
			}
			
			// Products
			$products = $this->em->getRepository('product\models\Product')->getProducts(array('vendor' => $purchase->getVendor()->getID()));
			
			// Order Frequency
			$frequency = $this->em->getRepository('purchase\models\Purchase')->getOrderFrequency($purchase->getVendor());			
			
			// Get purchased items
			$current_items = $purchase->getItems();
			foreach ($current_items as $item) {
				$purchased_items[$item->getProduct()->getID()] = $item;			
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
					   'ytd'				=> $ytd,
					   'payments'			=> $purchase->getPayments(),
					   'summary'			=> $purchase->getSummary(),
					   'boh_updated'		=> $purchase->getBohUpdated(),
					  'products'			=> $products,
					  'frequency'			=> $frequency,
					  'purchased_items'		=> $purchased_items,
					  'statuses' 			=> transaction\models\Transaction::getPurchaseStatuses(),
					  'payment_types'		=> transaction\models\Transaction::getPaymentTypes());
		
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
		$boh_updated = $purchase->getBohUpdated();
		
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
					
					if ($boh_updated == 0) {
						$current_item->setReceived($products[$current_item->getProduct()->getID()]['received']);
					}
					
					//$current_item->setComment($products[$current_item->getProduct()->getID()]['comment']);	
					
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
		
		// Update the current payments
		$current_payments = $purchase->getPayments();
		$payments = $this->input->post('payments');
		
		if ($current_payments) {
			foreach ($current_payments as $current_payment) {
				if(isset($payments[$current_payment->getID()])) {
					$current_payment->setStatus($payments[$current_payment->getID()]['status']);
					
					unset($payments[$current_payment->getID()]);
				}
			}
		}
		
		// Add new payments
		if ($payments) {
			foreach ($payments as $p) {
				if (!empty($p['amount'])) {
					$payment = new transaction\models\TransactionPayment;
					
					$payment->setPaymentType($p['payment_type']);
					$payment->setStatus($p['status']);
					$payment->setAmount($p['amount']);
					$payment->setComment($p['comment']);
					
					$purchase->addPayment($payment);
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
					$item->setReceived($prod['received']);
					//$item->setComment($prod['comment']);
					
					$purchase->addItem($item);
				}
			}
		}		
		
		// Update the current BOH, if this purchase has not been updated yet
		if ($this->input->post('update_boh') !== false) {
			$purchase->setBohUpdated($this->input->post('update_boh')); // Set boh_updated to 1 or 0
			$_updated_items = $purchase->getItems(); // Get all items			
			
			foreach($_updated_items as $_updated_item) {
				$_product = $_updated_item->getProduct();
				$_boh = $_product->getTotalQty();
				
				if ($this->input->post('update_boh') == 1) {
					$_boh += $_updated_item->getReceived();
				} else {
					$_boh -= $_updated_item->getReceived();
				}
				
				$_product->setTotalQty($_boh);
				$this->em->persist($_product);
			}
		}
		
		$summary = $purchase->getSummary();
		$purchase->setTotal($summary['sub_total']);
		
		$this->em->persist($purchase);
		$this->em->flush();
	}
}