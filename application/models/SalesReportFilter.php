<?php
include 'Filter.php';

class SalesReportFilter extends Filter {
    // Customer id
    protected $customer;
    
    protected $status;
    
    // Category id
    protected $category;
    
    function __construct($filter = array()) {
        parent::__construct($filter);
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getCustomer() {
        return $this->customer;
    }
    
    public function setCustomer($customer) {
        $this->customer = $customer;
    }
    
    public function setCategory($category) {
        $this->category = $category;
    }
    
    public function getCategory() {
        return $this->category;
    }
    
    public function setFrom($from) {
        if (!empty($from)) {
            if (is_string($from)) {
                $this->created_at_from = new DateTime($from);
            }
            if ($from instanceof DateTime) {
                $this->created_at_from = $form;
            }
        }
    }
    
    public function setTo($to) {
        if (!empty($to)) {
            if (is_string($to)) {
               $this->created_at_to = new DateTime($to . ' 23:59:59');
           }
           if ($to instanceof DateTime) {
               $this->created_at_to = $to;
           }
        }
    }
}

