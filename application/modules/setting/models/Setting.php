<?php

namespace setting\models;

/**
 * @Entity
 * @Table(name="setting")
 */
class Setting {
	/**
	 * @Id
	 * @Column(type="string")
	 */
	private $name;
	
	/**
	 * @Column(type="text", nullable=true)
	 */
	private $value;
    
	private static $columns = array('tax', 'currency', 'company', 'hst');
	
	public function __construct($name, $value) {
        $this->setName($name);
        $this->setValue($value);
    }
	
    public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getValue() {
		$result = @unserialize($this->value);
		
		if ($this->value === 'b:0;' || $result !== false) {
			return unserialize($this->value);
		} else {
			return $this->value;
		}
	}
	
	public function setValue($value) {
		if (is_array($value) || is_object($value)) {
			$this->value = serialize($value);
		} else {
			$this->value = $value;
		}
	}
	
	public static function getSettingFields() {
		return self::$columns;
	}
}