<?php
class Filter {
    const SORT_BY_DESC = 'DESC';
    const SORT_BY_ASC = 'ASC';
    
    protected $id;
    
    protected $created_at_from = null;
    
    protected $created_at_to = null;
    
    protected $order = 'created_at';
    
    protected $sort = self::SORT_BY_DESC;
    
    protected $limit = null; // default value 'null' is unlimited
    
    // Current page number
    protected $current_page = 0;
    
    protected $per_page = 10;
    
    function __construct($filter = array()) {
        $this->load($filter);
    }
    
    public function load($filter) {
        if (!empty($filter)) {
            foreach($filter as $key => $value) {
                $this->{$key} = $value;
                
                if ($key == 'from') {
                    $this->setFrom($value);
                }
                if ($key == 'to') {
                    $this->setTo($value);
                }
            }
        }
        
        if ($this->created_at_to == null) {
            $this->created_at_to = new DateTime();
        }
    }
    
    public function setCurrentPage($current){
        $this->current_page = $current;
    }
    
    public function setPerPage($per_page) {
        $this->per_page = $per_page;
    }
    
    public function getPerPage() {
        return $this->per_page;
    }
    
    public function setLimit($limit) {
        $this->limit = $limit;
    }
    
    public function setOrder($order) {
        $this->order($order);
    }
    
    public function setSort($sort) {
        if ($sort == self::SORT_BY_ASC || $sort == self::SORT_BY_DESC) {
            $this->sort = $sort;
        }
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
    
    public function toArray() {
        $_properties = get_object_vars($this);        
        
        foreach($_properties as $prop => $value) {
            if ($value !== null && $value !== '') {
                if ($value instanceof DateTime) {
                    $temp[$prop] = $value->format('Y-m-d H:i:s');
                } else {
                    $temp[$prop] = $value;
                }
            }
        }
        
        return $temp;
    }
    
    public function buildUrl() {}
}