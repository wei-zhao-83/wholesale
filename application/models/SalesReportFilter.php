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
    
    public function setRange($range) {
        switch($range) {
            case '7days':
                $this->created_at_from = new DateTime('-1 week');
            break;
            case 'ytd':
                $this->created_at_from = new DateTime('-1 year');
            break;
            case '2ytd':
                $this->created_at_from = new DateTime('-2 year');
            break;
            default:
            case 'month':
                $this->created_at_from = new DateTime('first day of this month');
            break;
        }
    }
}

