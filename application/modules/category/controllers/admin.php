<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	function  __construct() {
		parent::__construct();
	}
	
	public function index() {
		$filter = $this->input->post();
		if ($this->input->post('as_values_tags')) {
			$filter['tags'] = explode(',', $this->input->post('as_values_tags'));
		}
		
		$data = array('categories' => $this->em->getRepository('category\models\Category')->getCategories($filter));
		
		$this->load->view('admin/header');
		$this->load->view('admin/index', $data);
		$this->load->view('admin/footer');
	}
	
	public function create() {
		$category = new category\models\Category;
		
		$data = array('category' => $category);
		
		if ($this->_category_validate() !== FALSE) {	
			try {
				$this->_do($category);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Category successfully inserted.'));
				redirect('admin/category/edit/' . $category->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'Can not insert this category.'));
				redirect('admin/category');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/form_create', $data);
		$this->load->view('admin/footer');
	}
	
	public function edit() {
		$id = $this->uri->segment(4);
		$category = $this->em->getRepository('category\models\Category')->findOneBy(array('id' => $id));
		
		if (!$category) {
			redirect('admin/category');
		}
		
		$tags = $this->em->getRepository('tag\models\Tag')->findAll();
		
		$current_tags = array();
		foreach ($category->getTags() as $tag) {
			$current_tags[] = $tag->getName();
		}
		
		$data = array('category' => $category,
					  'tags' => $tags,
					  'current_tags' => implode(',', $current_tags));
		
		if ($this->_category_validate() !== FALSE) {
			try {
				$this->_do($category);
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Category successfully updated.'));
				redirect('admin/category/edit/' . $category->getId());
			} catch(Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
				redirect('admin/category');
			}
		} else {
			if ($this->form_validation->error_array()) {
				$data += array('message' => array('type' => 'error', 'content' => $this->form_validation->error_array()));
			}
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/form_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function delete() {
		$id = $this->uri->segment(4);
		
		$category = $this->em->getRepository('category\models\Category')->findOneBy(array('id' => $id));
		
		if ($no_products = $category->getProducts()->count() > 0) {
			$this->session->set_flashdata('message', array('type' => 'error', 'content' => 'There are ' . $no_products . ' products in this category.' ));
		} else {
			try {
				$category->setDeletedAt(new DateTime);
				$this->em->persist($category);
				$this->em->flush();
				
				$this->session->set_flashdata('message', array('type' => 'success', 'content' => 'Successfully deleted.'));
			} catch (Exception $e) {
				$this->session->set_flashdata('message', array('type' => 'error', 'content' => $e->getMessage()));
			}
		}
		
		redirect('admin/category');
	}
	
	private function _do(category\models\Category $category) {
		if ($this->input->post('edit')) {
			// remove the current tags
			$current_tags = $category->getTags();
			foreach ($current_tags as $current_tag) {
				$category->removeTag($current_tag);
			}
		}
		
		// add selected tags
		$tags = explode(',', $this->input->post('as_values_tags'));
		foreach($tags as $tag_name) {
			$tag = $this->em->getRepository('tag\models\Tag')->findOneBy(array('name' => $tag_name));
			if ($tag) {
				$category->addTag($tag);
			}
		}
		
		// Reflection object
		$reflected_model = new ReflectionClass('category\models\Category');
		
		foreach($this->input->post() as $field => $value) {
			$_method = 'set' . implode(array_map('ucfirst', explode('_', $field)));
			
			if ($reflected_model->hasMethod($_method)) {
				$category->$_method($value);
			}
		}			
		
		$this->em->persist($category);
		$this->em->flush();
	}
	
	private function _category_validate() {
		$_rules = $this->config->item('rule_category', 'validate');
		
		if($this->input->post('edit')) {
			$category = $this->em->getRepository('category\models\Category')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('slug') == $category->getSlug()) {
				unset($_rules['slug']);
			}
		}
		
		$this->form_validation->set_rules($_rules); 
		return $this->form_validation->run();
	}
}