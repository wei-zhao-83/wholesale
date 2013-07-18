<?php
include 'Filter.php';

class ProductsFilter extends Filter {
    protected $name;
    
    protected $barcode;
    
    protected $section;
    
    protected $vendor;
    
    protected $category;
    
    protected $tags = array();
    
    protected $order = 'name';
    
    function __construct($filter = array()) {
        if (!empty($filter)) {
            foreach($filter as $key => $value) {
                if ($key != 'tags') {
                    $this->{$key} = $value;
                }
            }
        }
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getBarcode() {
        return $this->barcode;
    }
    
    public function getSection() {
        return $this->section;
    }
    
    public function getVendor() {
        return $this->vendor;
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    public function getTags() {
        if (!empty($this->tags)) {
            return implode(',', $this->tags);
        }
        
        return '';
    }
    
    public function setTags($tags) {
        $this->tags = array_filter(explode(',', $tags));
    }
}

