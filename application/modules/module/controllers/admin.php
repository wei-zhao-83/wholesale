<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
    
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$data['modules'] = $this->em->getRepository('module\models\Module')->findAll();
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
}