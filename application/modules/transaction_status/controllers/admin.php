<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	function  __construct() {
		parent::__construct();
	}

	public function index() {
		$data = array('statuses' => $this->em->getRepository('transaction_status\models\TransactionStatus')->getStatuses());
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');		
	}
	
	public function create() {
		$status = new transaction_status\models\TransactionStatus;
		
		$data = array('status' => $status);
		
		if ($this->_status_validate() !== FALSE) {
			try {
				$this->_do($status);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Transaction status successfully inserted.'));
				redirect('admin/transaction_status/edit/' . $status->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this status.'));
				redirect('admin/transaction_status');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/create', $data);
		$this->load->view('admin/footer');
	}
	
	public function edit($id) {
		$status = $this->em->getRepository('transaction_status\models\TransactionStatus')->findOneBy(array('id' => $id));
		
		if (!$status || $status->getCore()) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not edit core status'));
			redirect('admin/transaction_status');
		}
		
		$data = array('status' => $status);
		
		if ($this->_status_validate() !== FALSE) {
			try {
				$this->_do($status);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Transaction status successfully updated.'));
				redirect('admin/transaction_status/edit/' . $status->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/transaction_status');
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
	
	public function delete($id) {
		$status = $this->em->getRepository('transaction_status\models\TransactionStatus')->findOneBy(array('id' => $id));
		
		if ($status->getCore()) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not delete core status'));
			redirect('admin/transaction_status');
		} else {
			try {
				// Soft Delete
				$status->setDeletedAt(new DateTime);
				$this->em->persist($status);
				$this->em->flush();
			
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
			} catch (Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
			}
			
			redirect('admin/transaction_status');
		}
	}
	
	private function _status_validate() {
		$status_validation_rule = array(
			array('field'=>'name',
				  'label'=>'Name',
				  'rules'=>'required|xss_clean|min_length[2]')
		);
		
		$this->form_validation->set_rules($status_validation_rule); 
		return $this->form_validation->run();
	}
	
	private function _do(transaction_status\models\TransactionStatus $status) {
		$status->setName(generate_slug($this->input->post('name')));
		$status->setCore(0);
		
		$this->em->persist($status);
		$this->em->flush();
	}
}