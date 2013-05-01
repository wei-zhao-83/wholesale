<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
    public function __construct() {
		parent::__construct();
	}
    
    public function index() {
        $this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/dashboard');
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
		$this->load->view('admin/menu');
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