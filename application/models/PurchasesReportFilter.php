<?php
include 'Filter.php';

class PurchasesReportFilter extends Filter {
    // Vendor id
    protected $vendor;
    
    protected $status;
    
    function __construct($filter = array()) {
        parent::__construct($filter);
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function getVendor() {
        return $this->vendor;
    }
}

