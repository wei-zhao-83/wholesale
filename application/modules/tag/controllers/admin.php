<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	function  __construct() {
		parent::__construct();		
	}
	
	public function ajax_search() {
		$tags = array();
		
		$temp_tags = $this->em->getRepository('tag\models\Tag')->getTags();
		foreach($temp_tags as $tag) {
			$tags[]['value'] = $tag->getName();
		}
		
		echo json_encode($tags, true);
	}
	
	public function index() {
		$filter = $this->input->post();
		
		$data = array('tags' => $this->em->getRepository('tag\models\Tag')->getTags($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');	
	}
	
	public function create() {
		$tag = new tag\models\Tag;
		
		$data = array('tag'  => $tag);
		
		if ($this->uri->segment(4) && $this->uri->segment(4) === 'ajax') {
			$is_ajax = true;
		} else {
			$is_ajax = false;
		}
		
		if ($this->_tag_validate() !== FALSE) {
			try {
				$this->_do($tag);
				
				if (!$is_ajax) {
					$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'tag successfully inserted.'));
					redirect('admin/tag/edit/' . $tag->getId());
				} else {
					$data += array('message' => array('type' => 'success', 'content' => 'tag successfully inserted.'));
				}
			} catch(Exception $e) {
				$data += array('message' => array('type' => 'error', 'content' => 'Can not insert this tag.'));
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		if ($is_ajax) {
			$this->load->view('admin/form_ajax_create', $data);
		} else {
			$this->load->view('admin/header');
			$this->load->view('admin/menu');
			$this->load->view('admin/form_create', $data);
			$this->load->view('admin/footer');
		}
	}
	
	public function edit() {
		$id = $this->uri->segment(4);
		$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('id' => $id));
		
		if (!$tag) {
			redirect('admin/tag');
		}
		
		$data = array('tag'  => $tag);
		
		if ($this->_tag_validate() !== FALSE) {
			try {
				$this->_do($tag);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'tag successfully updated.'));
				redirect('admin/tag/edit/' . $tag->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/tag');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/menu');
		$this->load->view('admin/form_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function delete() {
		$id = $this->uri->segment(4);
		
		try {
			$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('id' => $id));
			
			$this->em->remove($tag);
			$this->em->flush();
			
			$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
		} catch (Exception $e) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
		}
		
		redirect('admin/tag');
	}
	
	private function _do(tag\models\Tag $tag) {
		if ($tag instanceof tag\models\Tag) {
			$tag->setName(generate_slug($this->input->post('name')));
            
			$this->em->persist($tag);
			$this->em->flush();
		} else {
			throw new Exception('tag not exists.');
		}
	}
	
	private function _tag_validate() {
		$tag_validation_rule = array(
				'name' => array('field'=>'name',
								'label'=>'Name',
								'rules'=>'required|xss_clean|min_length[2]')
		);
		
		$this->form_validation->set_rules($tag_validation_rule); 
		return $this->form_validation->run();
	}
}