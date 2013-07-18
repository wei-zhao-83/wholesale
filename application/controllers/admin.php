<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
    public function __construct() {
		parent::__construct();
		
		$this->load->model('SalesReportFilter');
	}
	
    public function index() {
		$data = $sales_matrix = array();
		
		// Set sales filter
		$filter = new SalesReportFilter();
		$filter->setSort(Filter::SORT_BY_ASC);
		$filter->setStatus(transaction\models\Transaction::STATUS_COMPLETED);
		
		if (!empty($_GET['from'])) {
			$filter->setFrom($_GET['from']);
		}
		if (!empty($_GET['to'])) {
			$filter->setTo($_GET['to']);
		}
		if (!empty($_GET['range'])) {
			$filter->setRange($_GET['range']);
		}
		
		$sales_by_date = $this->em->getRepository('sale\models\Sale')->getSales($filter->toArray());		
		
		if (!empty($sales_by_date)) {
			foreach($sales_by_date as $sale) {
				$_datetime = new DateTime($sale->getCreatedAt());
				$_micro_timestamp = $_datetime->getTimestamp() . '000';
				
				$sales_matrix[$_micro_timestamp] = $sale->getTotal();
			}
		}		
		
		// Last 5 orders
		$last_order_filter = new SalesReportFilter();
		$last_order_filter->setLimit(5);
		
		$last_sales = $this->em->getRepository('sale\models\Sale')->getSales($last_order_filter->toArray());	
		
        $this->load->view('admin/header', array('current_view' => 'dashboard'));
		$this->load->view('admin/dashboard', array('sales_matrix' => json_encode($sales_matrix),
												   'last_sales' => $last_sales));
		$this->load->view('admin/footer');
    }
	
	public function login() {
		$logged_in = false;
		$data = array();
		
		if ($this->_login_validate() !== FALSE) {
			$logged_in = $this->auth->login($this->input->post('identity'), $this->input->post('password'));
			
			if (!$logged_in) {
				redirect('admin/login');
			}
		}
		
		if ($logged_in || $this->auth->is_logged_in()) {
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Welcome Back.'));
			redirect('admin');
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/login', $data);
		$this->load->view('admin/footer');
	}
	
	public function logout() {
		$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'You are now logged out.'));
		$this->auth->logout();
		
		redirect('admin/login');
	}
	
	private function _login_validate(){
		$login_validation_rule = array(
			array('field'=>'identity',
				  'label'=>'Identity',
				  'rules'=>'required'),
			array('field'=>'password',
				  'label'=>'Password',
				  'rules'=>'required')
		);
		
		$this->form_validation->set_rules($login_validation_rule); 
		return $this->form_validation->run();
	}
}