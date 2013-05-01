<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$filter = $this->input->post();
		$filter['to'] = $this->session->userdata('id');
		
		$data = array('messages' => $this->em->getRepository('message\models\Message')->getMessages($filter),
					  'users' => $this->em->getRepository('user\models\User')->findAll());
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');	
	}
	
	public function create() {
		$message = new message\models\Message;
		$users = $this->em->getRepository('user\models\User')->findAll();
		
		$data = array('msg'  => $message,
					  'users' => $users);
		
		if ($this->_message_validate() !== FALSE) {
			try {
				$this->_do($message);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Message successfully sent.'));
				redirect('admin/message');
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this message.'));
				redirect('admin/message');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/form_create', $data);
		$this->load->view('admin/footer');
	}
	
	public function delete() {
		$id = $this->uri->segment(4);
		
		try {
			$message = $this->em->getRepository('message\models\Message')->findOneBy(array('id' => $id));
			
			if ($message->getReceiver()->getId() != $this->session->userdata('id')) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Message not exists.'));
			} else {
				$this->em->remove($message);
				$this->em->flush();
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
			}
			
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/message');
	}
	
	public function view() {
		$id = $this->uri->segment(4);
		
		try {
			$message = $this->em->getRepository('message\models\Message')->findOneBy(array('id' => $id));
			$data = array('message' => $message);
			
			if ($message->getReceiver()->getId() != $this->session->userdata('id')) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Message not exists.'));
				redirect('admin/message');
			} else {
				$message->setUnread(0);
				$this->em->persist($message);
				$this->em->flush();
				
				$this->load->view('admin/header');
				$this->load->view('admin/menu');
				$this->load->view('admin/view', $data);
				$this->load->view('admin/footer');
			}
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
			redirect('admin/message');
		}
	}
	
	private function _do($message) {
		if ($message instanceof message\models\Message) {
			
			$to = $this->em->getRepository('user\models\User')->findOneBy(array('id' => $this->input->post('to')));
			$from = $this->em->getRepository('user\models\User')->findOneBy(array('id' => $this->session->userdata('id')));
			
			$message->setSubject($this->input->post('subject'));
			$message->setContent($this->input->post('content'));
			$message->setReceiver($to);
			$message->setSender($from);
			$message->setUnread(1);
			
			$this->em->persist($message);
			$this->em->flush();
		} else {
			throw new Exception('Message not exists.');
		}
	}
	
	private function _message_validate() {
		$message_validation_rule = array(
			array('field'=>'subject',
				  'label'=>'Subject',
				  'rules'=>'required'),
			array('field'=>'content',
				  'label'=>'Content',
				  'rules'=>'required'),
			array('field'=>'to',
				  'label'=>'To',
				  'rules'=>'required')
		);
		
		$this->form_validation->set_rules($message_validation_rule); 
		return $this->form_validation->run();
	}
}