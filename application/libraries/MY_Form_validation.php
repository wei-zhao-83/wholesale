<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function __construct() {
	    parent::__construct();
		
		$this->CI =& get_instance();
	}
    
	public function is_unique($value, $params) {
		list($model, $field) = explode(".", $params, 2);
		
		$qry_str = 'SELECT COUNT(x.id) FROM ' . $model . ' x
					WHERE x.'.$field.' = :' . $field . ' ';
					
		$query = $this->CI->doctrine->em->createQuery($qry_str);
		$query->setParameter($field, $value);
		$num_user = $query->getSingleScalarResult();
		
		if($num_user >= 1) {
			$this->CI->form_validation->set_message('unique', 'The %s is already being used.');
			return false;
		} else {
			return true;
		}
	}
	
	public function valid_phone_number($phone) {
        $phone = trim($phone);
        $match = '/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/';
        $replace = '/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
        $return = '($1) $2-$3';
        
        if (preg_match($match, $phone)) {
            return true;
        } else {
			$this->CI->form_validation->set_message('valid_phone_number', 'The %s field must contain a valid phone number.');
            return false;
        }
    }
	
	public function is_money($price) {
		if (preg_match('/^[0-9]+(\.[0-9]{0,2})?$/', $price)) {
			return true;
		} else {
			$this->CI->form_validation->set_message('is_money', 'The %s field must contain a valid format.');
            return false;
		}		
	}
	
	function error_array() {
        if (count($this->_error_array) === 0) {
            return FALSE;
        } else {
            return $this->_error_array;
		}
    }
}

?>