<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends MY_Controller {	
    public function __construct() {
		parent::__construct();
		
        if ( ! self::_check_access()) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Access Denied'));
			redirect('admin');
		}
		
		// Todo: Theme
    }
    
    private function _check_access() {
		$ignored_pages = array('admin/login', 'admin/logout');
		$current_page = $this->controller . '/' . $this->method;
		
		if (in_array($current_page, $ignored_pages)) {
			return true;
		}
		
		// check user login
		if (!$this->current_user) {
			redirect('admin/login');
		}
		
		// check user permissions
        if ($this->method == 'index' && empty($this->module)) {
			return true;
		} else {
			return in_array($this->module . '/' . $this->method, $this->permissions);
		}
		
        return true;
    }
}