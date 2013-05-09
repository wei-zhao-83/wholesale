<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {
	
	private $upload_path = '';
	
	function  __construct() {
		parent::__construct();
		
		$config['upload_path'] = $this->upload_path = $this->config->item('category_image_upload_path', 'config_site');
		$config['allowed_types'] = $this->config->item('category_image_allowed_types', 'config_site');
		$config['max_size']	= $this->config->item('category_image_max_size', 'config_site');
		$config['max_width']  = $this->config->item('category_image_max_width', 'config_site');
		$config['max_height']  = $this->config->item('category_image_max_height', 'config_site');
		
		$this->load->library('upload', $config);
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
		
		$post_tags = ($this->input->post('as_values_tags'))?trim($this->input->post('as_values_tags')):'';
		
		$data = array('category' => $category,
					  'post_tags' => $post_tags);
		
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
		if ($category instanceof category\models\Category) {
            if ($this->input->post('edit')) {
				// remove the current tags
				$current_tags = $category->getTags();
				foreach ($current_tags as $current_tag) {
					$category->removeTag($current_tag);
				}
				
				// remove the current image
				$current_images = $category->getImages();
				foreach ($current_images as $current_image) {
					$category->removeImage($current_image);
					$this->em->remove($current_image);
				}
				
				// update the current images
				if ($this->input->post('current_category_images')) {
					foreach ($this->input->post('current_category_images') as $id => $value) {
						$image = new image\models\Image;
						$image->setName($value['name']);
						$image->setPath($value['path']);
						$image->setAlt($value['alt']);
						$image->setArrange($value['arrange']);
						$image->setMain($value['main']);
						
						$category->addImage($image);
					}
				}
			}
			
			// upload images first to folder
			foreach($_FILES as $key => $value):
				if ($this->upload->do_upload($key)):
					$array_key = (int)str_replace('image_file_', '', $key);
					$category_image_array[$array_key] = $this->upload->data();
				endif;
			endforeach;
			
			// add images
			foreach ($this->input->post('category_images') as $key => $value) {
				if(isset($category_image_array[$key]['file_name'])) {
					$image = new image\models\Image;
					$image->setName($value['name']);
					$image->setPath($this->upload_path . $category_image_array[$key]['file_name']);
					$image->setAlt($value['alt']);
					$image->setArrange($value['arrange']);
					$image->setMain($value['main']);
					
					$category->addImage($image);
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
			
			$category->setName($this->input->post('name'));
			$category->setSlug(generate_slug($this->input->post('slug')));
			$category->setArrange($this->input->post('arrange'));
			$category->setActive($this->input->post('active'));
            $category->setDescription($this->input->post('description'));
            
			$category->setSEOTitle($this->input->post('seo_title'));
			$category->setSEOURL($this->input->post('seo_url'));
			$category->setSEOCanonicalLink($this->input->post('seo_canonical_link'));
			$category->setSEOKeywords($this->input->post('seo_keywords'));
			$category->setSEORobots($this->input->post('seo_robots'));
			
			$this->em->persist($category);
			$this->em->flush();
		} else {
			throw new Exception('category not exists.');
		}
	}
	
	private function _category_validate() {
		$category_validation_rule = array(
			//[0]
			array('field'=>'name',
				  'label'=>'Name',
				  'rules'=>'required|xss_clean|min_length[3]'),
			//[1]
			array('field'=>'slug',
				  'label'=>'Slug',
				  'rules'=>'required|xss_clean|min_length[3]|is_unique[category\models\Category.slug]'),
			//[2]
			array('field'=>'arrange',
				  'label'=>'Arrange',
				  'rules'=>'numeric'),
			//[3]
			array('field'=>'active',
				  'label'=>'Active',
				  'rules'=>'required'),
            //[4]
			array('field'=>'description',
				  'label'=>'Description',
				  'rules'=>'max_length[250]'),
			array('field'=>'seo_title',
				  'label'=>'Page Title',
				  'rules'=>''),
			array('field'=>'seo_url',
				  'label'=>'URL',
				  'rules'=>''),
			array('field'=>'seo_canonical_link',
				  'label'=>'Canonical Link',
				  'rules'=>''),
			array('field'=>'seo_keywords',
				  'label'=>'Keywords',
				  'rules'=>''),
			array('field'=>'seo_robots',
				  'label'=>'Robots',
				  'rules'=>'')
		);
		
		if($this->input->post('edit')) {
			$category = $this->em->getRepository('category\models\Category')->findOneBy(array('id' => $this->input->post('id')));
			
			if($this->input->post('slug') == $category->getSlug()) {
				unset($category_validation_rule[1]);
			}
		}
		
		$this->form_validation->set_rules($category_validation_rule); 
		return $this->form_validation->run();
	}
}