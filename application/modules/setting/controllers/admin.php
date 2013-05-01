<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {	
	public function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$settings = $this->em->getRepository('setting\models\Setting');
		
		foreach (setting\models\Setting::getSettingFields() as $column) {
			$data[$column] = $settings->findOneByName($column)->getValue();
		}
		
		if ($this->_setting_validate() !== FALSE) {
			try {
				foreach (setting\models\Setting::getSettingFields() as $column) {
					if ($this->input->post($column)) {
						$save[$column] = $settings->findOneByName($column);
						$save[$column]->setValue($this->input->post($column));
						
						$this->em->persist($save[$column]);
					}
				}
				
				$this->em->flush();
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully updated.'));
				redirect('admin/setting/');
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
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
	
	/**
	 * Setting validation function
	 *
	 * @access private
	 * @return bool
	 */
	private function _setting_validate() {
		$setting_validation_rule = array(
			'tax' => array('field'=>'tax',
							'label'=>'Tax',
							'rules'=>'required|is_money'),
			'currency' => array('field'=>'currency',
							'label'=>'Currency',
							'rules'=>'required')
		);
		
		$this->form_validation->set_rules($setting_validation_rule); 
		return $this->form_validation->run();
	}
}