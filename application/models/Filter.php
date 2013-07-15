<?php
class Filter {
    const SORT_BY_DESC = 'DESC';
    const SORT_BY_ASC = 'ASC';
    
    protected $id;
    
    protected $created_at_from = null;
    
    protected $created_at_to = null;
    
    protected $order = 'created_at';
    
    protected $sort = self::SORT_BY_DESC;
    
    function __construct($filter = array()) {
        $this->load($filter);
    }
    
    public function load($filter) {
        if (!empty($filter)) {
            foreach($filter as $key => $value) {
                $this->{$key} = $value;
            }
        }
        
        if ($this->created_at_to == null) {
            $this->created_at_to = new DateTime();
        }
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
        if (is_string($from)) {
            $this->created_at_from = new DateTime($from);
        }
        if ($from instanceof DateTime) {
            $this->created_at_from = $form;
        }
    }
    
    public function setTo($to) {
         if (is_string($to)) {
            $this->created_at_to = new DateTime($to . ' 23:59:59');
        }
        if ($to instanceof DateTime) {
            $this->created_at_to = $to;
        }
    }
    
    public function toArray() {
        $temp = array();
        
        $_properties = get_object_vars($this);
        
        foreach($_properties as $prop => $value) {
            if (!empty($this->{$prop})) {
                if ($this->{$prop} instanceof DateTime) {
                    $temp[$prop] = $this->{$prop}->format('Y-m-d H:i:s');
                } else {
                    $temp[$prop] = $this->{$prop};
                }
            }
        }
        
        return $temp;
    }
    
    public function buildUrl() {}
}