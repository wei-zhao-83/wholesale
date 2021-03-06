<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	function  __construct() {
		parent::__construct();
		
		$this->load->model('SalesReportFilter');
	}
	
	public function report() {
		$data = $products = $items = array();
		$category_id = !empty($_GET['category']) ? $_GET['category'] : null;
		
		if ($category_id != null) {
			try {
				// Order items by category
				$order_items_filter = new SalesReportFilter();
				$order_items_filter->setCategory($category_id);
				$order_items_filter->setStatus(Transaction\models\Transaction::STATUS_COMPLETED);
				$order_items_filter->setFrom($_GET['from']);
				$order_items_filter->setTo($_GET['to']);
				
				$order_items = $this->em->getRepository('sale\models\Sale')->getSaleItemsByCategory($order_items_filter->toArray());
				
				foreach($order_items as $item) {
					if (!isset($products[$item->getProduct()->getID()])) {
						$products[$item->getProduct()->getID()] = array('obj' => $item->getProduct(),
																		'qty' => $item->getQty(),
																		'total' => $item->getRowTotal());
					} else {
						$products[$item->getProduct()->getID()]['total'] += $item->getRowTotal();
						$products[$item->getProduct()->getID()]['qty'] += $item->getQty();
					}
					
					$_datetime = new DateTime($item->getTransaction()->getCreatedAt());
					$_micro_timestamp = $_datetime->getTimestamp() . '000';					
					
					if (isset($items[$_micro_timestamp])) {
						$items[$_micro_timestamp] += $item->getRowTotal();
					} else {
						$items[$_micro_timestamp] = $item->getRowTotal();
					}
				}
			} catch (Exception $e) {
				$data['message'] = array('type' => 'error', 'content' => $e->getMessage());
			}
		}
		
		// Sort the result
		ksort($items);
		
		$data = array('products' => $products,
					'items' => $items,
					'categories' => $this->em->getRepository('category\models\Category')->getCategories(),
					'customers' => $this->em->getRepository('customer\models\Customer')->getCustomers());
		
		$this->load->view('admin/header', array('current_view' => 'report'));
		$this->load->view('admin/report', $data);
		$this->load->view('admin/footer');
	}
	
	public function index() {
		$filter = new SalesReportFilter($_GET);
		$filter->setCurrentPage($this->uri->segment(4, 0));
		
		$sales = $this->em->getRepository('sale\models\Sale')->getSales($filter->toArray());
		
		// Pagination
		$pagination	= create_pagination('admin/sale/index/', count($sales), $filter->toArray());
		
		$data = array('sales' 	   => $sales,
					  'customers'  => $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'statuses'   => transaction\models\Transaction::getSaleStatuses(),
					  'pagination' => $pagination,
					  'filter'	   => $filter);
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function invoice($id) {
		$sale = $this->em->getRepository('sale\models\Sale')->findOneById($id);
		$settings = $this->em->getRepository('setting\models\Setting');		
		
		$data = array('sale' 	=> $sale,
					  'summary' => $sale->getSummary(true),
					  'hst' 	=> $settings->findOneByName('hst')->getValue(),
					  'company' => $settings->findOneByName('company')->getValue(),
					  'tax' 	=> $settings->findOneByName('tax')->getValue());
		
		$this->load->view('admin/invoice', $data);
	}
	
	public function picklist($id) {
		$sale = $this->em->getRepository('sale\models\Sale')->findOneById($id);
		$settings = $this->em->getRepository('setting\models\Setting');
		
		if ($_POST) {
			$picked = $this->input->post('picked');
			$shipped = $this->input->post('shipped');
			
			// Update the current BOH, if this purchase has not been updated yet
			if ($this->input->post('update_boh') !== false) {
				$sale->setBohUpdated($this->input->post('update_boh')); // Set boh_updated to 1 or 0
			}
			
			foreach($sale->getItems() as $item) {
				if (isset($picked[$item->getID()])) {
					$item->setPicked($picked[$item->getID()]);
				}
				if (isset($shipped[$item->getID()])) {
					$item->setShipped($shipped[$item->getID()]);
				}
				
				// Update the current BOH, if this purchase has not been updated yet
				if ($this->input->post('update_boh') !== false) {
					$_product = $item->getProduct();
					$_boh = $_product->getTotalQty();
					
					if ($this->input->post('update_boh') == 1) {
						$_boh -= $item->getPicked();
					} else {
						$_boh += $item->getPicked();
					}
					
					$_product->setTotalQty($_boh);
					$this->em->persist($_product);
				}
				
				$this->em->persist($item);
			}
			
			$this->em->flush();
		} // end of post
		
		$data = array('sale' 		=> $sale,
					  'boh_updated' => $sale->getBohUpdated(),
					  'company' 	=> $settings->findOneByName('company')->getValue());	
		
		$this->load->view('admin/picklist', $data);
	}
	
	public function create() {
		$sale = new sale\models\Sale;
		
		try {
			$this->_do($sale);
			redirect('admin/sale/edit/' . $sale->getId());
		} catch(Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/sale');
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
					  'boh_updated'			=> $sale->getBohUpdated(),
					  'payments'			=> $sale->getPayments(),
					  'selected_customer' 	=> $selected_customer,
					  'categories'  		=> $this->em->getRepository('category\models\Category')->getCategories(),
					  'customers' 			=> $this->em->getRepository('customer\models\Customer')->getCustomers(),
					  'vendors'				=> $this->em->getRepository('vendor\models\Vendor')->getVendors(),
					  'statuses' 			=> transaction\models\Transaction::getSaleStatuses(),
					  'types'				=> sale\models\Sale::getTypes(),
					  'payment_types'		=> transaction\models\Transaction::getPaymentTypes());
		
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
		$status = ($this->input->post('status')) ? $this->input->post('status') : transaction\models\Transaction::STATUS_DRAFT;
		
		$sale->setUser($this->current_user);
		$sale->setCustomer($customer);
		$sale->setStatus($status);
		$sale->setShipDate($this->input->post('ship_date'));
		
		if ($_POST) {
			$sale->setDefaultDiscount($this->input->post('default_discount'));
			//$sale->setPayment($this->input->post('payment'));
			$sale->setType($this->input->post('type'));
			$sale->setComment($this->input->post('comment'));
		}
		
		$current_items = $sale->getItems();
		$products = $this->input->post('products');
		
		if (!empty($current_items)) {
			foreach($current_items as $current_item) {
				// Update the current items, if current item exists in the post products array
				if(isset($products[$current_item->getProduct()->getID()]) && !empty($products[$current_item->getProduct()->getID()]['qty'])) {
					$current_item->setQty($products[$current_item->getProduct()->getID()]['qty']);
					$current_item->setComment($products[$current_item->getProduct()->getID()]['comment']);
					
					if (!$current_item->getProduct()->getNoDiscount()) {
						$current_item->setDiscount($products[$current_item->getProduct()->getID()]['discount']);
					}					
					
					$current_item->setCNC(null);
					$current_item->setFullServicePrice(null);
					$current_item->setNoServicePrice(null);
					
					if ($sale->getType() == sale\models\Sale::TYPE_CNC) {
						$current_item->setCNC($current_item->getProduct()->getCNC());
					} elseif ($sale->getType() == sale\models\Sale::TYPE_FULL) {
						$current_item->setFullServicePrice($current_item->getProduct()->getFullServicePrice());
					} elseif ($sale->getType() == sale\models\Sale::TYPE_STANDARD) {
						$current_item->setNoServicePrice($current_item->getProduct()->getNoServicePrice());
					}
					
					$this->em->persist($current_item);
					
					// Unset it from the post products array
					unset($products[$current_item->getProduct()->getID()]);
				// Delete the removed items
				} else {
					$sale->removeItem($current_item);
					$this->em->remove($current_item);
				}
			}
		}
		
		// Update the current payments
		$current_payments = $sale->getPayments();
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
					
					$sale->addPayment($payment);
				}
			}
		}
		
		// Add new item
		if (!empty($products)) {
			foreach ($products as $prod_id => $prod) {
				if (!empty($prod['qty'])) {
					$product = $this->em->getRepository('product\models\Product')->findOneById($prod_id);
					
					$item = new transaction\models\TransactionItem;
					
					$item->setProduct($product);
					$item->setCost($product->getCost());
					$item->setSuggestedPrice($product->getSuggestedPrice());
					$item->setTax($this->tax);
					$item->setQty($prod['qty']);
					$item->setComment($prod['comment']);
					
					if (!$product->getNoDiscount()) {
						$item->setDiscount($prod['discount']);
					}
					
					$sale->addItem($item);
				}
			}
		}
		
		// Set the total
		$summary = $sale->getSummary();
		$sale->setTotal($summary['sub_total']);
		
		$this->em->persist($sale);
		$this->em->flush();
	}
}