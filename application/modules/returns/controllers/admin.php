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
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create($order_id = null) {
		//$sale = null;
		//if (!empty($order_id)) {
		//	$sale = $this->em->getRepository('sale\models\Sale')->findOneById($order_id);
		//}
		
		$return = new returns\models\Returns;
		
		if ($_POST) {
			try {
				$customer = $this->em->getRepository('customer\models\Customer')->findOneById($this->input->post('customer'));
				$status = ($this->input->post('status')) ? $this->input->post('status') : transaction\models\Transaction::STATUS_COMPLETED;
				
				$return->setUser($this->current_user);
				$return->setCustomer($customer);
				$return->setStatus($status);
				
				// Update BOH
				// Calculate the total credits
				
				$this->em->persist($return);
				$this->em->flush();
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not create this return.'));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/edit', array('return' 	   => $return,
											  'customers'  => $this->em->getRepository('customer\models\Customer')->getCustomers(),
											  'categories' => $this->em->getRepository('category\models\Category')->getCategories(),
											  'vendors'    => $this->em->getRepository('vendor\models\Vendor')->getVendors()));
		$this->load->view('admin/footer');
	}
}