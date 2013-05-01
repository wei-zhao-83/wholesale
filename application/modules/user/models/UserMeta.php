<?php

namespace user\models;

/**
 * @Entity
 * @Table(name="user_meta")
 */
class UserMeta {
	/**
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
	private $id;
	
	/**
	 * @Column(type="string", length=255, nullable=false)
	 */
	private $meta_key;
	
	/**
	 * @Column(type="text", nullable=true)
	 */
	private $meta_value;
	
	/**
	 * Bidirectional - Many user metas are belong to one user (OWNING SIDE)
	 * 
     * @ManyToOne(targetEntity="User", inversedBy="user_metas")
     */
	private $user;
	
	public function getId() {
		return $this->id;
	}
	
	public function getKey() {
		return $this->meta_key;
	}
	
	public function setKey($meta_key) {
		$this->meta_key = $meta_key;
	}
	
	public function getValue() {
		$result = @unserialize($this->meta_value);
		
		if ($this->meta_value === 'b:0;' || $result !== false) {
			return unserialize($this->meta_value);
		} else {
			return $this->meta_value;
		}
	}
	
	public function setValue($meta_value) {
		if (is_array($meta_value) || is_object($meta_value)) {
			$this->meta_value = serialize($meta_value);
		} else {
			$this->meta_value = $meta_value;
		}
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function setUser(User $user) {
		$this->user = $user;
	}
}

?>